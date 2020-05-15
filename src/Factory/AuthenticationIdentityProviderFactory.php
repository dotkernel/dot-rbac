<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Factory;

use Laminas\Authentication\AuthenticationService;
use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Psr\Container\ContainerInterface;

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
        return new $requestedName($container->get(AuthenticationService::class));
    }
}
