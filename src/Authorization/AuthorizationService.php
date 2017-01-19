<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac\Authorization;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Exception\InvalidArgumentException;
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
     * @var AssertionPluginManager
     */
    protected $assertionPluginManager;

    /**
     * @var AssertionInterface[]
     */
    protected $assertions = [];

    /**
     * AuthorizationService constructor.
     * @param RbacInterface $rbac
     * @param RoleServiceInterface $roleService
     * @param AssertionPluginManager $assertionPluginManager
     */
    public function __construct(
        RbacInterface $rbac,
        RoleServiceInterface $roleService,
        AssertionPluginManager $assertionPluginManager
    ) {
        $this->rbac = $rbac;
        $this->roleService = $roleService;
        $this->assertionPluginManager = $assertionPluginManager;
    }

    /**
     * @param $permission
     * @param $assertion
     */
    public function addAssertion($permission, $assertion)
    {
        $this->assertions[(string)$permission] = $assertion;
    }

    /**
     * @param array $assertions
     */
    public function setAssertions(array $assertions)
    {
        $this->assertions = $assertions;
    }

    /**
     * @return \Dot\Authorization\Identity\IdentityInterface
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
     * @param null $context
     * @return bool
     */
    public function isGranted($permission, array $roles = [], $context = null)
    {
        if (empty($roles)) {
            $roles = $this->roleService->getIdentityRoles();
        }

        if (empty($roles)) {
            return false;
        }

        if (!$this->rbac->isGranted($roles, $permission)) {
            return false;
        }

        if ($this->hasAssertion($permission)) {
            return $this->assert($this->assertions[(string)$permission], $context);
        }

        return true;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasAssertion($permission)
    {
        return isset($this->assertions[(string)$permission]);
    }

    /**
     * @param $assertion
     * @param null $context
     * @return bool
     */
    protected function assert($assertion, $context = null)
    {
        if (is_callable($assertion)) {
            return $assertion($this, $context);
        } elseif ($assertion instanceof AssertionInterface) {
            return $assertion->assert($this, $context);
        } elseif (is_string($assertion)) {
            $assertion = $this->assertionPluginManager->get($assertion);
            return $assertion->assert($this, $context);
        }

        throw new InvalidArgumentException(sprintf(
            'Assertion must be a callable or implement %s, "%s" given',
            AssertionInterface::class,
            is_object($assertion) ? get_class($assertion) : gettype($assertion)
        ));
    }
}
