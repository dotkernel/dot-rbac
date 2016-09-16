<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/21/2016
 * Time: 3:35 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Role\RoleProviderPluginManager;
use Interop\Container\ContainerInterface;

class RoleProviderPluginManagerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dk_authorization']['role_provider_manager'];

        $pluginManager = new RoleProviderPluginManager($container, $config);
        return $pluginManager;
    }
}