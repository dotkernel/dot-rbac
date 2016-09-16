<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/21/2016
 * Time: 2:13 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Options\ModuleOptions;
use Interop\Container\ContainerInterface;

class ModuleOptionsFactory
{
    /**
     * @param ContainerInterface $container
     * @return ModuleOptions
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ModuleOptions($container->get('config')['dk_authorization']);
    }
}