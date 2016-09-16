<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Factory\AssertionPluginManagerFactory;
use Dot\Rbac\Factory\AuthenticationIdentityProviderFactory;
use Dot\Rbac\Factory\AuthorizationServiceFactory;
use Dot\Rbac\Factory\ModuleOptionsFactory;
use Dot\Rbac\Factory\RoleProviderPluginManagerFactory;
use Dot\Rbac\Factory\RoleServiceFactory;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\ModuleOptions;
use Dot\Rbac\Role\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleService;

/**
 * Class ModuleConfig
 * Config provider to be used with mtymek/expressive-config-manager
 *
 * @package N3vrax\DkRbac
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'invokables' => [
                    RbacInterface::class => Rbac::class
                ],
                'factories' => [
                    IdentityProviderInterface::class => AuthenticationIdentityProviderFactory::class,

                    RoleProviderPluginManager::class => RoleProviderPluginManagerFactory::class,

                    RoleService::class => RoleServiceFactory::class,

                    ModuleOptions::class => ModuleOptionsFactory::class,

                    AssertionPluginManager::class => AssertionPluginManagerFactory::class,

                    AuthorizationInterface::class => AuthorizationServiceFactory::class,
                ]
            ],

            'dk_authorization' => [
                'guest_role' => 'guest',

                'assertion_map' => [

                ],

                'assertion_manager' => [

                ],

                'role_provider' => [
                    
                ],

                'role_provider_manager' => [

                ],
            ]
        ];
    }
}