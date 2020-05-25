<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Identity\IdentityInterface;
use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Role\Provider\RoleProviderInterface;

/**
 * Class RoleService
 * @package Dot\Rbac\Role
 */
class RoleService implements RoleServiceInterface
{
    /**
     * @var IdentityProviderInterface
     */
    protected $identityProvider;

    /**
     * @var RoleProviderInterface
     */
    protected $roleProvider;

    /**
     * @var string
     */
    protected $guestRole = 'guest';

    /**
     * RoleService constructor.
     * @param IdentityProviderInterface $identityProvider
     * @param RoleProviderInterface $roleProvider
     */
    public function __construct(
        IdentityProviderInterface $identityProvider,
        RoleProviderInterface $roleProvider
    ) {
        $this->identityProvider = $identityProvider;
        $this->roleProvider = $roleProvider;
    }

    /**
     * @param IdentityProviderInterface $identityProvider
     */
    public function setIdentityProvider(IdentityProviderInterface $identityProvider)
    {
        $this->identityProvider = $identityProvider;
    }

    /**
     * @param RoleProviderInterface $roleProvider
     */
    public function setRoleProvider(RoleProviderInterface $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }

    /**
     * @return string
     */
    public function getGuestRole(): string
    {
        return $this->guestRole;
    }

    /**
     * @param string $guestRole
     */
    public function setGuestRole(string $guestRole)
    {
        $this->guestRole = $guestRole;
    }

    /**
     * Check if the given roles match one of the identity roles
     *
     * This method is smart enough to automatically recursively extracts roles for hierarchical roles
     *
     * @param  string[]|RoleInterface[] $roles
     * @return bool
     */
    public function matchIdentityRoles(array $roles): bool
    {
        $identityRoles = $this->getIdentityRoles();
        // Too easy...
        if (empty($identityRoles)) {
            return false;
        }
        $roleNames = [];
        foreach ($roles as $role) {
            $roleNames[] = $role instanceof RoleInterface ? $role->getName() : (string)$role;
        }

        $identityRoleNames = [];
        foreach ($this->flattenRoles($identityRoles) as $role) {
            $identityRoleNames[] = $role->getName();
        }

        return count(array_intersect($roleNames, $identityRoleNames)) > 0;
    }

    /**
     * Get the identity roles from the current identity
     *
     * @return RoleInterface[]
     */
    public function getIdentityRoles(): array
    {
        if (!$identity = $this->getIdentity()) {
            return $this->convertRoles([$this->guestRole]);
        }
       
        return $this->convertRoles($identity->getRoles());
    }

    /**
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->identityProvider->getIdentity();
    }

    /**
     * @param array $roles
     * @return string|string[]|RoleInterface|RoleInterface[]|\Traversable
     */
    protected function convertRoles(array $roles): array
    {
        if ($roles instanceof \Traversable) {
            $roles = iterator_to_array($roles);
        }
        $collectedRoles = [];
        $toCollect = [];
        foreach ((array)$roles as $role) {
            // If it's already a RoleInterface, nothing to do as a RoleInterface contains everything already
            if ($role instanceof RoleInterface) {
                $collectedRoles[] = $role;
                continue;
            }

            if (method_exists($role, 'getName')) {
                $role = $role->getName();
            }
            // Otherwise, it's a string and hence we need to collect it
            $toCollect[] = (string)$role;
        }
        // Nothing to collect, we don't even need to hit the (potentially) costly role provider
        if (empty($toCollect)) {
            return $collectedRoles;
        }
        return array_merge($collectedRoles, $this->roleProvider->getRoles($toCollect));
    }

    /**
     * Flatten an array of role with role names
     *
     * This method iterates through the list of roles, and convert any RoleInterface to a string. For any
     * role, it also extracts all the children
     *
     * @param  array|RoleInterface[] $roles
     * @return \Generator
     */
    protected function flattenRoles(array $roles): \Generator
    {
        foreach ($roles as $role) {
            yield $role;

            if (!$role instanceof HierarchicalRoleInterface) {
                continue;
            }

            $children = $this->flattenRoles($role->getChildren());

            foreach ($children as $child) {
                yield $child;
            }
        }
    }
}
