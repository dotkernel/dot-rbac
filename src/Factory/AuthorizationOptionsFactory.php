<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Options\AuthorizationOptions;
use Interop\Container\ContainerInterface;

/**
 * Class AuthorizationOptionsFactory
 * @package Dot\Rbac\Factory
 */
class AuthorizationOptionsFactory
{
    /**
     * @param ContainerInterface $container
     * @return AuthorizationOptions
     */
    public function __invoke(ContainerInterface $container)
    {
        return new AuthorizationOptions($container->get('config')['dot_authorization']);
    }
}
