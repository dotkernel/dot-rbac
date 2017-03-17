<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Options\AuthorizationOptions;
use Psr\Container\ContainerInterface;

/**
 * Class AuthorizationOptionsFactory
 * @package Dot\Rbac\Factory
 */
class AuthorizationOptionsFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return AuthorizationOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        return new $requestedName($container->get('config')['dot_authorization']);
    }
}
