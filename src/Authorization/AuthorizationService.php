<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Authorization;

use Dot\Authorization\AuthorizationInterface;
use Dot\Authorization\Identity\IdentityInterface;
use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;

/**
 * Class AuthorizationService
 * @package Dot\Rbac\Authorization
 */
class AuthorizationService implements AuthorizationInterface
{
    /**
     * @var RbacInterface
     */
    protected $rbac;

    /**
     * @var RoleServiceInterface
     */
    protected $roleService;

    /**
     * @var Factory
     */
    protected $assertionFactory;

    /**
     * @var array
     */
    protected $assertions = [];

    /**
     * AuthorizationService constructor.
     * @param RbacInterface $rbac
     * @param RoleServiceInterface $roleService
     * @param Factory $assertionFactory
     */
    public function __construct(
        RbacInterface $rbac,
        RoleServiceInterface $roleService,
        Factory $assertionFactory
    ) {
        $this->rbac = $rbac;
        $this->roleService = $roleService;
        $this->assertionFactory = $assertionFactory;
    }

    /**
     * @param string $permission
     * @param mixed $assertion
     */
    public function addAssertion(string $permission, $assertion)
    {
        if (!is_array($assertion) && !$assertion instanceof AssertionInterface) {
            throw new RuntimeException(
                sprintf('Assertion must be an array or an instance of `%s`', AssertionInterface::class)
            );
        }

        if (!isset($this->assertions[$permission])) {
            $this->assertions[$permission] = [];
        }

        $this->assertions[$permission][] = $assertion;
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->roleService->getIdentity();
    }

    /**
     * If no roles supplied, use the identity roles, if one
     *
     * @param string $permission
     * @param array $roles
     * @param mixed $context
     * @return bool
     */
    public function isGranted(string $permission, array $roles = [], $context = null): bool
    {
        if (empty($roles)) {
            $roles = $this->roleService->getIdentityRoles();
        }

        if (empty($roles)) {
            return false;
        }

        if (!$this->rbac->isGranted($permission, $roles)) {
            return false;
        }

        if ($this->hasAssertion($permission)) {
            return $this->assert($this->assertions[$permission], $context);
        }

        return true;
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasAssertion(string $permission): bool
    {
        return isset($this->assertions[$permission]) && !empty($this->assertions[$permission]);
    }

    /**
     * @param array $assertions
     * @param mixed $context
     * @return bool
     */
    protected function assert(array $assertions, $context = null): bool
    {
        $allow = true;
        foreach ($assertions as $assertion) {
            if (is_array($assertion) && !empty($assertion)) {
                $assertion = $this->assertionFactory->create($assertion);
            }

            if (($allow = $assertion->assert($this, $context)) === false) {
                break;
            }
        }
        return $allow;
    }
}
