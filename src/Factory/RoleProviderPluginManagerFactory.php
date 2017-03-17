<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Psr\Container\ContainerInterface;

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
        return new RoleProviderPluginManager($container, $config);
    }
}
