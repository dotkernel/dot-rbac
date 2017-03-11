<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

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
        return new AssertionPluginManager($container, $config);
    }
}
