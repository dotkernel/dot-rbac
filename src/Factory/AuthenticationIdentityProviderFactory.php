<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/16/2016
 * Time: 2:12 PM
 */

namespace Dot\Rbac\Factory;

use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Interop\Container\ContainerInterface;
use N3vrax\DkAuthentication\AuthenticationInterface;

class AuthenticationIdentityProviderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $authentication = $container->has(AuthenticationInterface::class)
            ? $container->get(AuthenticationInterface::class)
            : null;

        if(!$authentication) {
            throw new \Exception("AuthenticationInterface service is missing");
        }

        return new AuthenticationIdentityProvider($authentication);
    }
}