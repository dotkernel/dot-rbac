<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Factory\AssertionPluginManagerFactory;
use Dot\Rbac\Factory\AuthenticationIdentityProviderFactory;
use Dot\Rbac\Factory\AuthorizationServiceFactory;
use Dot\Rbac\Factory\AuthorizationOptionsFactory;
use Dot\Rbac\Factory\RoleProviderPluginManagerFactory;
use Dot\Rbac\Factory\RoleServiceFactory;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleServiceInterface;

/**
 * Class ConfigProvider
 * @package Dot\Rbac
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

                    RoleServiceInterface::class => RoleServiceFactory::class,

                    AuthorizationOptions::class => AuthorizationOptionsFactory::class,

                    AssertionPluginManager::class => AssertionPluginManagerFactory::class,

                    AuthorizationInterface::class => AuthorizationServiceFactory::class,
                ]
            ],

            'dot_authorization' => [

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