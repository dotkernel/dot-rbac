<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/16/2016
 * Time: 5:29 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Interop\Container\ContainerInterface;

class AssertionPluginManagerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dk_authorization']['assertion_manager'];
        $pluginManager = new AssertionPluginManager($container, $config);

        return $pluginManager;
    }
}