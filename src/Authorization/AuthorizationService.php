<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/13/2016
 * Time: 9:46 PM
 */

namespace Dot\Rbac\Authorization;

use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleService;
use N3vrax\DkAuthorization\AuthorizationInterface;
use N3vrax\DkAuthorization\Exception\RuntimeException;
use N3vrax\DkAuthorization\Identity\IdentityInterface;

class AuthorizationService implements AuthorizationInterface
{
    /**
     * @var RbacInterface
     */
    protected $rbac;

    /**
     * @var RoleService
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

    public function __construct(
        RbacInterface $rbac,
        RoleService $roleService,
        AssertionPluginManager $assertionPluginManager)
    {
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
        $this->assertions[(string) $permission] = $assertion;
    }

    /**
     * @param array $assertions
     */
    public function setAssertions(array $assertions)
    {
        $this->assertions = $assertions;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasAssertion($permission)
    {
        return isset($this->assertions[(string) $permission]);
    }

    /**
     * @return IdentityInterface|null
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
        if(empty($roles)) {
            $roles = $this->roleService->getIdentityRoles();
        }

        if(empty($roles))
        {
            return false;
        }

        if(!$this->rbac->isGranted($roles, $permission))
        {
            return false;
        }

        if($this->hasAssertion($permission)) {
            return $this->assert($this->assertions[(string) $permission], $context);
        }

        return true;
    }

    /**
     * @param $assertion
     * @param null $context
     * @return bool
     */
    protected function assert($assertion, $context = null)
    {
        if (is_callable($assertion))
        {
            return $assertion($this, $context);
        }
        elseif ($assertion instanceof AssertionInterface)
        {
            return $assertion->assert($this, $context);
        }
        elseif(is_string($assertion))
        {
            $assertion = $this->assertionPluginManager->get($assertion);
            return $assertion->assert($this, $context);
        }

        throw new RuntimeException(sprintf(
            'Assertion must be a callable or implement %s, "%s" given',
            AssertionInterface::class,
            is_object($assertion) ? get_class($assertion) : gettype($assertion)
        ));
    }
}