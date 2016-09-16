<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/17/2016
 * Time: 8:46 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\ModuleOptions;
use Dot\Rbac\Role\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleService;
use Interop\Container\ContainerInterface;

class RoleServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $moduleOptions = $container->get(ModuleOptions::class);

        $identityProvider = $container->get(IdentityProviderInterface::class);
        
        $roleProviderConfig = $moduleOptions->getRoleProvider();
        if(empty($roleProviderConfig)) {
            throw new RuntimeException('No role provider was set for authorization');
        }

        $pluginManager = $container->get(RoleProviderPluginManager::class);

        $roleProvider = $pluginManager->get(key($roleProviderConfig), current($roleProviderConfig));

        $service = new RoleService($identityProvider, $roleProvider);
        $service->setGuestRole($moduleOptions->getGuestRole());

        return $service;

    }
}