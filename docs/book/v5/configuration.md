# Configuration

You can configure the authorization service in code, but we recommend the configuration file.
This page shows how to configure the package with a configuration file.

First, merge the `ConfigProvider` of this package with the configuration of your application.
The `ConfigProvider` registers all the services of this package in your container.
It also sets the default configuration values.

Then create a configuration file in your `config/autoload` folder and change the options as necessary.

## authorization.global.php

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

        // a flat RBAC model with the InMemoryRoleProvider
        'role_provider' => [
            'type'    => 'InMemory',
            'options' => [
                'roles' => [
                    'admin' => [
                        'permissions' => [
                            'edit',
                            'delete',
                        ],
                    ],
                    'user'  => [
                        'permissions' => [
                            'view',
                        ],
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

## A hierarchical model

A role can have child roles.
A role then has its own permissions and the permissions of all its child roles.

```php
<?php

declare(strict_types=1);

return [
    'dot_authorization' => [
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
    ],
];
```

A role hierarchy must not have a cycle.
For example, `admin` must not be a child of `user` when `user` is a child of `admin`.
The `InMemoryRoleProvider` throws a `Dot\Rbac\Exception\RuntimeException` when it finds a cycle.

## Plugin managers

The keys `assertion_manager` and `role_provider_manager` accept two keys:

- `factories`: a map of plugin name to factory. A factory is a class name or a callable. It gets the container, the plugin name and the `options`.
- `aliases`: a map of alias to plugin name.

Use `Dot\Rbac\Plugin\InvokableFactory` for a class that has no constructor arguments, or one array argument.
The `InMemoryRoleProvider` is registered by default, with the aliases `InMemory`, `inMemory` and `inmemory`.
