<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

declare(strict_types = 1);

namespace Dot\Rbac\Factory;

use Dot\Authentication\AuthenticationInterface;
use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Interop\Container\ContainerInterface;

/**
 * Class AuthenticationIdentityProviderFactory
 * @package Dot\Rbac\Factory
 */
class AuthenticationIdentityProviderFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return AuthenticationIdentityProvider
     */
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        return new $requestedName($container->get(AuthenticationInterface::class));
    }
}
