<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

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
     * @return AuthenticationIdentityProvider
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container)
    {
        $authentication = $container->has(AuthenticationInterface::class)
            ? $container->get(AuthenticationInterface::class)
            : null;

        if (!$authentication) {
            throw new \Exception("AuthenticationInterface service is missing");
        }

        return new AuthenticationIdentityProvider($authentication);
    }
}
