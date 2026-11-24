# dot-rbac

Rbac authorization model implements [dot-authorization](https://github.com/dotkernel/dot-authorization)'s `AuthorizationInterface`.
An authorization service decides if the authenticated identity or guest has access to certain parts of the application.

The RBAC model defines roles that you assign to users.
The authorization uses roles, not users as in ACL.
Each role can have one or more permissions.
To decide if a user has access, the service looks for the requested permission in all the roles of the user.
If one role has the permission, the user has access.

## Documentation

Documentation is available at: https://docs.dotkernel.org/dot-rbac/.

## Versions

| Version | Container | PHP |
| --- | --- | --- |
| 5.x | Any PSR-11 container. `laminas/laminas-servicemanager` is not a requirement | 8.3 or higher |
| 4.x | `laminas/laminas-servicemanager` 4.x | 8.2 to 8.5 |
| 3.x | `laminas/laminas-servicemanager` 3.x | 8.2 to 8.5 |

To upgrade from an older version, read the [upgrade guide](https://docs.dotkernel.org/dot-rbac/v5/upgrading/).

## Badges

![OSS Lifecycle](https://img.shields.io/osslifecycle/dotkernel/dot-rbac)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-rbac/5.0.0)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-rbac)](https://github.com/dotkernel/dot-rbac/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-rbac)](https://github.com/dotkernel/dot-rbac/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-rbac)](https://github.com/dotkernel/dot-rbac/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-rbac)](https://github.com/dotkernel/dot-rbac/blob/5.0/LICENSE.md)

[![Build Static](https://github.com/dotkernel/dot-rbac/actions/workflows/continuous-integration.yml/badge.svg?branch=5.0)](https://github.com/dotkernel/dot-rbac/actions/workflows/continuous-integration.yml)
[![codecov](https://codecov.io/gh/dotkernel/dot-rbac/graph/badge.svg?token=GCK6C92N83)](https://codecov.io/gh/dotkernel/dot-rbac)
[![PHPStan](https://github.com/dotkernel/dot-rbac/actions/workflows/static-analysis.yml/badge.svg?branch=5.0)](https://github.com/dotkernel/dot-rbac/actions/workflows/static-analysis.yml)

## Installation

Run the following command in your project root directory:

```shell
composer require dotkernel/dot-rbac
```

The package works with any PSR-11 container that accepts a factory for each service key, for example Laminas Service Manager.
If your application uses Laminas Service Manager, require `laminas/laminas-servicemanager` in your application's `composer.json`.

## Configuration

You can configure the authorization service in code, but we recommend the configuration file.

First, merge the `ConfigProvider` of this package with the configuration of your application.
The `ConfigProvider` registers all the services of this package in your container.
It also sets the default configuration values.

Then create a configuration file in your `config/autoload` folder and change the options as necessary.
The file `authorization.global.php.dist` in this repository is an example.

### authorization.global.php

```php
<?php

declare(strict_types=1);

use App\Assertion\EditAssertion;
use Dot\Rbac\Plugin\InvokableFactory;

return [
    'dot_authorization' => [
        // the name of the guest role, used when there is no authenticated identity
        'guest_role' => 'guest',

        // more role providers, in the same format as 'assertion_manager'
        'role_provider_manager' => [],

        // a hierarchical RBAC model with the InMemoryRoleProvider
        'role_provider' => [
            'type'    => 'InMemory',
            'options' => [
                'roles' => [
                    'admin' => [
                        'children'    => ['user'],
                        'permissions' => ['create', 'delete'],
                    ],
                    'user'  => [
                        'children'    => ['guest'],
                        'permissions' => ['edit'],
                    ],
                    'guest' => [
                        'permissions' => ['view'],
                    ],
                ],
            ],
        ],

        // the factories and aliases that create your assertions
        'assertion_manager' => [
            'factories' => [
                EditAssertion::class => InvokableFactory::class,
            ],
        ],

        // the assertions and the permissions that they apply to
        'assertions' => [
            [
                'type'        => EditAssertion::class,
                'permissions' => ['edit'],
                'options'     => [],
            ],
        ],
    ],
];
```

`App\Assertion\EditAssertion` is an example class.
Replace it with your own assertion, or remove the `assertion_manager` factory and the `assertions` entry.

A role hierarchy must not have a cycle.
The `InMemoryRoleProvider` throws a `Dot\Rbac\Exception\RuntimeException` when it finds a cycle.

## Usage

To check if someone has access to an action, inject the `AuthorizationInterface::class` service into your class.
Then call the `isGranted` method.
There are two ways to call the `isGranted` method.

### First method

Specify the roles that you want to check.
A role can be a role name or an object that implements `Dot\Authorization\Role\RoleInterface`.

```php
$isGranted = $this->authorizationService->isGranted('edit', ['admin', 'user']);
```

### Second method

Do not specify the roles, or send an empty array as the second parameter.
The service then checks the roles of the authenticated identity.
If there is no authenticated identity, the service checks the guest role.

```php
$isGranted = $this->authorizationService->isGranted('edit');
```

## Customize the IdentityProvider

When you check the authorization of the authenticated identity, the `AuthorizationService` gets the identity from the `IdentityProviderInterface` service.

The identity depends on the authentication, so you can replace this service.
The default implementation gets the identity from `Laminas\Authentication\AuthenticationService`.
To get the identity from a different source, register your own implementation of `IdentityProviderInterface` in your container.

## Custom role providers

To write your own role provider, implement `RoleProviderInterface`.
Then register a factory for it under `role_provider_manager.factories` in the configuration file.

## Creating assertions

The service checks the assertions after it finds the permission, before it returns the result.
An assertion can deny access that the role permissions allow.
For example, an assertion can allow the `edit` permission only when the user id is the same as the owner id of the item.
You write the logic of the assertion.

An assertion implements `AssertionInterface`.
Register a factory for it under `assertion_manager.factories` in the configuration file.

The interface defines this method:

```php
public function assert(AuthorizationInterface $authorization, mixed $context = null): bool;
```

The `$context` parameter is the third parameter of `isGranted`.
It can be any data that the assertion needs to make the decision.
The assertion returns `true` to allow access and `false` to deny access.
