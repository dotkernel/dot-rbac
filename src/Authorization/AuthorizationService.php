<?php

declare(strict_types=1);

namespace Dot\Rbac\Authorization;

use Dot\Authorization\AuthorizationInterface;
use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Exception\InvalidArgumentException;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;

use function is_array;

class AuthorizationService implements AuthorizationInterface
{
    /** @var array<string, list<AssertionInterface|array<string, mixed>>> */
    protected array $assertions = [];

    public function __construct(
        protected RbacInterface $rbac,
        protected RoleServiceInterface $roleService,
        protected Factory $assertionFactory
    ) {
    }

    /**
     * @param AssertionInterface|array<string, mixed> $assertion
     */
    public function addAssertion(string $permission, AssertionInterface|array $assertion): void
    {
        if ($assertion === []) {
            throw new InvalidArgumentException('An assertion specification must not be empty');
        }

        $this->assertions[$permission][] = $assertion;
    }

    public function getIdentity(): mixed
    {
        return $this->roleService->getIdentity();
    }

    /**
     * @param array<string|RoleInterface> $roles
     */
    public function isGranted(string $permission, array $roles = [], mixed $context = null): bool
    {
        if ($roles === []) {
            $roles = $this->roleService->getIdentityRoles();
        } else {
            $roles = $this->roleService->resolveRoles($roles);
        }

        if ($roles === []) {
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
        return ! empty($this->assertions[$permission]);
    }

    /**
     * @param array<AssertionInterface|array<string, mixed>> $assertions
     */
    protected function assert(array $assertions, mixed $context = null): bool
    {
        foreach ($assertions as $assertion) {
            if (is_array($assertion)) {
                $assertion = $this->assertionFactory->create($assertion);
            }

            if (! $assertion->assert($this, $context)) {
                return false;
            }
        }

        return true;
    }
}
