<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Role\Provider\RoleProviderInterface;
use Generator;

use function array_intersect;
use function array_merge;
use function count;
use function method_exists;

class RoleService implements RoleServiceInterface
{
    protected IdentityProviderInterface $identityProvider;
    protected RoleProviderInterface $roleProvider;
    protected string $guestRole = 'guest';

    public function __construct(
        IdentityProviderInterface $identityProvider,
        RoleProviderInterface $roleProvider
    ) {
        $this->identityProvider = $identityProvider;
        $this->roleProvider     = $roleProvider;
    }

    public function setIdentityProvider(IdentityProviderInterface $identityProvider)
    {
        $this->identityProvider = $identityProvider;
    }

    public function setRoleProvider(RoleProviderInterface $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }

    public function getGuestRole(): string
    {
        return $this->guestRole;
    }

    public function setGuestRole(string $guestRole)
    {
        $this->guestRole = $guestRole;
    }

    public function matchIdentityRoles(array $roles): bool
    {
        $identityRoles = $this->getIdentityRoles();
        if (empty($identityRoles)) {
            return false;
        }
        $roleNames = [];
        foreach ($roles as $role) {
            $roleNames[] = $role instanceof RoleInterface ? $role->getName() : (string) $role;
        }

        $identityRoleNames = [];
        foreach ($this->flattenRoles($identityRoles) as $role) {
            $identityRoleNames[] = $role->getName();
        }

        return count(array_intersect($roleNames, $identityRoleNames)) > 0;
    }

    public function getIdentityRoles(): array
    {
        if (! $identity = $this->getIdentity()) {
            return $this->convertRoles([$this->guestRole]);
        }

        return $this->convertRoles($identity->getRoles());
    }

    public function getIdentity(): mixed
    {
        return $this->identityProvider->getIdentity();
    }

    protected function convertRoles(array $roles): array
    {
        $collectedRoles = [];
        $toCollect      = [];
        foreach ($roles as $role) {
            if ($role instanceof RoleInterface) {
                $collectedRoles[] = $role;
                continue;
            }

            if (method_exists($role, 'getName')) {
                $role = $role->getName();
            }
            $toCollect[] = (string) $role;
        }
        if (empty($toCollect)) {
            return $collectedRoles;
        }

        return array_merge($collectedRoles, $this->roleProvider->getRoles($toCollect));
    }

    protected function flattenRoles(array $roles): Generator
    {
        foreach ($roles as $role) {
            yield $role;

            if (! $role instanceof HierarchicalRoleInterface) {
                continue;
            }

            $children = $this->flattenRoles($role->getChildren());

            foreach ($children as $child) {
                yield $child;
            }
        }
    }
}
