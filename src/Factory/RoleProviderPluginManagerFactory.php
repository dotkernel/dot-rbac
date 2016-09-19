<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Role\RoleProviderPluginManager;
use Interop\Container\ContainerInterface;

/**
 * Class RoleProviderPluginManagerFactory
 * @package Dot\Rbac\Factory
 */
class RoleProviderPluginManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @return RoleProviderPluginManager
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dot_authorization']['role_provider_manager'];

        $pluginManager = new RoleProviderPluginManager($container, $config);
        return $pluginManager;
    }
}