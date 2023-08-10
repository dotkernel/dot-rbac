# dot-rbac

Rbac authorization model implements [dot-authorization](https://github.com/dotkernel/dot-authorization)'s `AuthorizationInterface`. An authorization service is responsible for deciding if the authenticated identity or guest has access to certain parts of the application.
The RBAC model defines roles that can be assigned to users. The authorization is done on a role basis, not user basis as in ACL.
Each role can have one or multiple permissions/privileges assigned. When deciding if a user is authorized, the requested permission is checked in all user roles and if at least one role has that permission, access is granted.

![OSS Lifecycle](https://img.shields.io/osslifecycle/dotkernel/dot-rbac)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-rbac/3.4.0)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-rbac)](https://github.com/dotkernel/dot-rbac/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-rbac)](https://github.com/dotkernel/dot-rbac/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-form)](https://github.com/dotkernel/dot-rbac/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-rbac)](https://github.com/dotkernel/dot-rbac/blob/3.3.0/LICENSE.md)

[![Build Static](https://github.com/dotkernel/dot-rbac/actions/workflows/static-analysis.yml/badge.svg?branch=3.0)](https://github.com/dotkernel/dot-rbac/actions/workflows/static-analysis.yml)

[![SymfonyInsight](https://insight.symfony.com/projects/ce0cfbb2-7e97-427b-b394-531ff5be13d6/big.svg)](https://insight.symfony.com/projects/ce0cfbb2-7e97-427b-b394-531ff5be13d6)

## Installation

Run the following command in your project root directory

```bash
$ composer require dotkernel/dot-rbac
```

## Configuration

Even if the authorization service can be programmatically configured, we recommend using the configuration based approach.
We further describe how to configure the module, using configuration file.

First of all, you should enable the module in your application by merging this package's `ConfigProvider` with your application's config.
This ensures that all dependencies required by this module are registered in the service manager. It also defines some sane defaults config values for this module.

Create a configuration file in your `config/autoload` folder and change module options as desired

##### authorization.global.php
```php
'dot_authorization' => [
    //name of the guest role to use if no identity is provided
    'guest_role' => 'guest',
    
    'role_provider_manager' => [],
    
    //example for a flat RBAC model using the InMemoryRoleProvider
    'role_provider' => [
        'type' => 'InMemory',
        'options' => [
            'roles' => [
                'admin' => [
                    'permissions' => [
                        'edit',
                        'delete',
                        //etc..
                    ]
                ],
                'user' => [
                    'permissions' => [
                        //...
                    ]
                ]
            ]
        ],
    ],
    
    //example for a hierarchical model, less to write but it can be confusing sometimes
    /*'role_provider' => [
        'type' => 'InMemory',
        'options' => [
            'roles' => [
                'admin' => [
                    'children' => ['user'],
                    'permissions' => ['create', 'delete']
                ],
                'user' => [
                    'children' => ['guest']
                    'permissions' => ['edit']
                ]
                'guest' => [
                    'permissions' => ['view']
                ]
            ]
        ]
    ],*/
    
    'assertion_manager' => [
        'factories' => [
            //EditAssertion::class => InvokableFactory::class,
        ],
    ],
    
    'assertions' => [
        [
            'type' => EditAssertion::class,
            'permissions' => ['edit'],
            'options' => []
        ]
    ]
]
```

## Usage

Whenever you need to check if someone is authorized to take some actions, inject the `AuthorizationInterface::class` service into your class.
Using it is simple as calling the isGranted method with the correct parameters. There are 2 ways to call the isGranted method
```php

//first method, specifiy which roles do you want to check
$isGranted = $this->authorizationService->isGranted($permission, $roles);

//...

//second method, do not specify the roles, or send an empty array, this will check if the authenticated identity has permission
$isGranted = $this->authorizationService->isGranted($permission);

```

## Customize the IdentityProvider

Whenever you request an authorization check on the authenticated identity, the identity will be provided to the AuthorizationService through a registered IdentityProviderInterface service.
This is because identity is authentication dependent, so the module lets you overwrite this service, depending on your needs.
If you want to get the identity from other sources instead of the dot-authentication service, just overwrite the 

`IdentityProviderInterface::class` service in the service manager with your own implementation of this interface.


## Custom role providers

Write your own role provider by implementing the `RoleProviderInterface` and register it in the RoleProviderPluginManager.
After that, you can use them in the configuration file, as described above.

## Creating assertions

Assertions are checked after permission granting, right before returning the authorization result.
Assertions can have a last word in deciding if someone is authorized for the requested action.
A good assertion example could be an edit permission, but with the restriction that it should be able to edit the item just if the user id is matching with the item's owner id.
It is up to you to write the logic inside an assertion.

An assertion has to implement the `AssertionInterface` and be registered in the AssertionPluginManager.

This interface defines the following method

```php
public function assert(AuthorizationInterface $authorization, $context = null);
```

The context variable can be any external data that an assertion needs in order to decide the authorization status.
The assertion must return a boolean value, reflecting the assertion pass or failure status.
