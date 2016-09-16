<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/13/2016
 * Time: 9:50 PM
 */

namespace Dot\Rbac\Role;

use Dot\Rbac\Identity\IdentityProviderInterface;
use N3vrax\DkAuthorization\Exception\RuntimeException;
use N3vrax\DkAuthorization\Identity\IdentityInterface;
use N3vrax\DkAuthorization\Role\RoleInterface;

class RoleService
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
    )
    {
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
     * @return IdentityInterface|null
     */
    public function getIdentity()
    {
        return $this->identityProvider->getIdentity();
    }

    /**
     * @param string $guestRole
     */
    public function setGuestRole($guestRole)
    {
        $this->guestRole = $guestRole;
    }

    /**
     * @return string
     */
    public function getGuestRole()
    {
        return $this->guestRole;
    }

    /**
     * Get the identity roles from the current identity
     *
     * @return RoleInterface[]
     */
    public function getIdentityRoles()
    {
        if(!$identity = $this->getIdentity()) {
            return $this->convertRoles([$this->guestRole]);
        }

        if(!$identity instanceof
            IdentityInterface)
        {
            throw new RuntimeException(sprintf(
                'Identity must implement %s, "%s" given',
                IdentityInterface::class,
                is_object($identity) ? get_class($identity) : gettype($identity)
            ));
        }

        return $this->convertRoles($identity->getRoles());
    }

    /**
     * Check if the given roles match one of the identity roles
     *
     * This method is smart enough to automatically recursively extracts roles for hierarchical roles
     *
     * @param  string[]|RoleInterface[] $roles
     * @return bool
     */
    public function matchIdentityRoles(array $roles)
    {
        $identityRoles = $this->getIdentityRoles();
        // Too easy...
        if (empty($identityRoles)) {
            return false;
        }
        $roleNames = [];
        foreach ($roles as $role) {
            $roleNames[] = $role instanceof RoleInterface ? $role->getName() : (string) $role;
        }

        $identityRoleNames = [];
        foreach ($this->flattenRoles($identityRoles) as $role)
        {
            $identityRoleNames[] = $role->getName();
        }

        return count(array_intersect($roleNames, $identityRoleNames)) > 0;
    }


    /**
     * @param $roles
     *
     * @return string|string[]|RoleInterface|RoleInterface[]|\Traversable
     */
    public function convertRoles($roles)
    {
        if ($roles instanceof \Traversable) {
            $roles = iterator_to_array($roles);
        }
        $collectedRoles = [];
        $toCollect      = [];
        foreach ((array) $roles as $role) {
            // If it's already a RoleInterface, nothing to do as a RoleInterface contains everything already
            if ($role instanceof RoleInterface) {
                $collectedRoles[] = $role;
                continue;
            }
            // Otherwise, it's a string and hence we need to collect it
            $toCollect[] = (string) $role;
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
     * @return RoleInterface[]
     */
    protected function flattenRoles(array $roles)
    {
        foreach ($roles as $role)
        {
            yield $role;

            if(!$role instanceof  HierarchicalRoleInterface) {
                continue;
            }

            $children = $this->flattenRoles($role->getChildren());

            foreach ($children as $child) {
                yield $child;
            }
        }
    }

}