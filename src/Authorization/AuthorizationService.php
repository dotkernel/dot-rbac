<?php

declare(strict_types=1);

namespace Dot\Rbac\Authorization;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;

use function is_array;
use function sprintf;

class AuthorizationService implements AuthorizationInterface
{
    protected RbacInterface $rbac;
    protected RoleServiceInterface $roleService;
    protected Factory $assertionFactory;
    protected array $assertions = [];

    public function __construct(
        RbacInterface $rbac,
        RoleServiceInterface $roleService,
        Factory $assertionFactory
    ) {
        $this->rbac             = $rbac;
        $this->roleService      = $roleService;
        $this->assertionFactory = $assertionFactory;
    }

    public function addAssertion(string $permission, mixed $assertion): void
    {
        if (! is_array($assertion) && ! $assertion instanceof AssertionInterface) {
            throw new RuntimeException(
                sprintf('Assertion must be an array or an instance of `%s`', AssertionInterface::class)
            );
        }

        if (! isset($this->assertions[$permission])) {
            $this->assertions[$permission] = [];
        }

        $this->assertions[$permission][] = $assertion;
    }

    public function getIdentity(): mixed
    {
        return $this->roleService->getIdentity();
    }

    public function isGranted(string $permission, array $roles = [], mixed $context = null): bool
    {
        if (empty($roles)) {
            $roles = $this->roleService->getIdentityRoles();
        }

        if (empty($roles)) {
            return false;
        }

        if (! $this->rbac->isGranted($permission, $roles)) {
            return false;
        }

        if ($this->hasAssertion($permission)) {
            return $this->assert($this->assertions[$permission], $context);
        }

        return true;
    }

    public function hasAssertion(string $permission): bool
    {
        return isset($this->assertions[$permission]) && ! empty($this->assertions[$permission]);
    }

    protected function assert(array $assertions, mixed $context = null): bool
    {
        $allow = true;
        foreach ($assertions as $assertion) {
            if (is_array($assertion) && ! empty($assertion)) {
                $assertion = $this->assertionFactory->create($assertion);
            }

            if (($allow = $assertion->assert($this, $context)) === false) {
                break;
            }
        }
        return $allow;
    }
}
