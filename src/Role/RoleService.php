<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Identity\IdentityInterface;
use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Exception\InvalidArgumentException;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Role\Provider\RoleProviderInterface;
use Generator;

use function array_intersect;
use function array_merge;
use function count;
use function get_debug_type;
use function is_array;
use function is_int;
use function is_object;
use function is_string;
use function method_exists;
use function sprintf;

class RoleService implements RoleServiceInterface
{
    protected string $guestRole = 'guest';

    public function __construct(
        protected IdentityProviderInterface $identityProvider,
        protected RoleProviderInterface $roleProvider
    ) {
    }

    public function setIdentityProvider(IdentityProviderInterface $identityProvider): void
    {
        $this->identityProvider = $identityProvider;
    }

    public function setRoleProvider(RoleProviderInterface $roleProvider): void
    {
        $this->roleProvider = $roleProvider;
    }

    public function getGuestRole(): string
    {
        return $this->guestRole;
    }

    public function setGuestRole(string $guestRole): void
    {
        $this->guestRole = $guestRole;
    }

    /**
     * @param array<string|RoleInterface> $roles
     */
    public function matchIdentityRoles(array $roles): bool
    {
        $identityRoles = $this->getIdentityRoles();
        if ($identityRoles === []) {
            return false;
        }

        $roleNames = [];
        foreach ($roles as $role) {
            $roleNames[] = $role instanceof RoleInterface ? $role->getName() : $role;
        }

        $identityRoleNames = [];
        foreach ($this->flattenRoles($identityRoles) as $role) {
            $identityRoleNames[] = $role->getName();
        }

        return count(array_intersect($roleNames, $identityRoleNames)) > 0;
    }

    /**
     * @return array<RoleInterface>
     */
    public function getIdentityRoles(): array
    {
        $identity = $this->getIdentity();
        if ($identity === null) {
            return $this->resolveRoles([$this->guestRole]);
        }

        if ($identity instanceof IdentityInterface) {
            return $this->resolveRoles($identity->getRoles());
        }

        if (is_object($identity) && method_exists($identity, 'getRoles')) {
            $roles = $identity->getRoles();
            if (is_array($roles)) {
                return $this->resolveRoles($roles);
            }
        }

        throw new RuntimeException(sprintf(
            'The identity must implement %s, %s given',
            IdentityInterface::class,
            get_debug_type($identity)
        ));
    }

    public function getIdentity(): mixed
    {
        return $this->identityProvider->getIdentity();
    }

    /**
     * @param array<mixed> $roles
     * @return array<RoleInterface>
     */
    public function resolveRoles(array $roles): array
    {
        $collectedRoles = [];
        $toCollect      = [];
        foreach ($roles as $role) {
            if ($role instanceof RoleInterface) {
                $collectedRoles[] = $role;
                continue;
            }

            if (is_string($role) || is_int($role)) {
                $toCollect[] = (string) $role;
                continue;
            }

            if (is_object($role) && method_exists($role, 'getName')) {
                $name = $role->getName();
                if (is_string($name)) {
                    $toCollect[] = $name;
                    continue;
                }
            }

            throw new InvalidArgumentException(sprintf('Invalid role of type %s', get_debug_type($role)));
        }

        if ($toCollect === []) {
            return $collectedRoles;
        }

        return array_merge($collectedRoles, $this->roleProvider->getRoles($toCollect));
    }

    /**
     * @param array<RoleInterface> $roles
     * @return Generator<int, RoleInterface>
     */
    protected function flattenRoles(array $roles): Generator
    {
        return RoleFlattener::flatten($roles);
    }
}
