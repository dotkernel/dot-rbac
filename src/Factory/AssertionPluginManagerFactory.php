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
use Interop\Container\ContainerInterface;

/**
 * Class AssertionPluginManagerFactory
 * @package Dot\Rbac\Factory
 */
class AssertionPluginManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AssertionPluginManager
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dot_authorization']['assertion_manager'];
        $pluginManager = new AssertionPluginManager($container, $config);

        return $pluginManager;
    }
}
