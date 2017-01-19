<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;
use Interop\Container\ContainerInterface;

/**
 * Class AuthorizationServiceFactory
 * @package Dot\Rbac\Factory
 */
class AuthorizationServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return AuthorizationService
     */
    public function __invoke(ContainerInterface $container)
    {
        $rbac = $container->get(RbacInterface::class);
        $roleService = $container->get(RoleServiceInterface::class);
        $assertionManager = $container->get(AssertionPluginManager::class);

        $service = new AuthorizationService($rbac, $roleService, $assertionManager);

        $moduleOptions = $container->get(AuthorizationOptions::class);
        $service->setAssertions($moduleOptions->getAssertionMap());

        return $service;
    }
}
