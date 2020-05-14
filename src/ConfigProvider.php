<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Factory\AssertionPluginManagerFactory;
use Dot\Rbac\Factory\AuthenticationIdentityProviderFactory;
use Dot\Rbac\Factory\AuthorizationOptionsFactory;
use Dot\Rbac\Factory\AuthorizationServiceFactory;
use Dot\Rbac\Factory\RoleProviderPluginManagerFactory;
use Dot\Rbac\Factory\RoleServiceFactory;
use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleService;
use Dot\Rbac\Role\RoleServiceInterface;
use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * Class ConfigProvider
 * @package Dot\Rbac
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),

            'dot_authorization' => [

                'guest_role' => 'guest',

                'assertions' => [],

                'assertion_manager' => [],

                'role_provider' => [],

                'role_provider_manager' => [],
            ]
        ];
    }

    public function getDependencyConfig(): array
    {
        return [
            'factories' => [
                Rbac::class => InvokableFactory::class,
                AuthenticationIdentityProvider::class => AuthenticationIdentityProviderFactory::class,
                RoleProviderPluginManager::class => RoleProviderPluginManagerFactory::class,
                RoleService::class => RoleServiceFactory::class,
                AuthorizationOptions::class => AuthorizationOptionsFactory::class,
                AssertionPluginManager::class => AssertionPluginManagerFactory::class,
                AuthorizationService::class => AuthorizationServiceFactory::class,
            ],
            'aliases' => [
                RbacInterface::class => Rbac::class,
                AuthorizationInterface::class => AuthorizationService::class,
                RoleServiceInterface::class => RoleService::class,
                IdentityProviderInterface::class => AuthenticationIdentityProvider::class,
            ],
        ];
    }
}
