<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/16/2016
 * Time: 2:11 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Options\ModuleOptions;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleService;
use Interop\Container\ContainerInterface;

class AuthorizationServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $rbac = $container->get(RbacInterface::class);

        $roleService = $container->get(RoleService::class);

        $assertionManager = $container->get(AssertionPluginManager::class);

        $service = new AuthorizationService($rbac, $roleService, $assertionManager);

        $moduleOptions = $container->get(ModuleOptions::class);

        $service->setAssertions($moduleOptions->getAssertionMap());

        return $service;
    }
}