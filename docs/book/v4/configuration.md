# Configuration

Even if the authorization service can be programmatically configured, we recommend using the configuration based approach.
We further describe how to configure the module, using the configuration file.

First of all, you should enable the module in your application by merging this package's `ConfigProvider` with your application's config.
This ensures that all dependencies required by this module are registered in the service manager.
It also defines default config values for this module.

Create a configuration file in your `config/autoload` folder and change the module options as needed.

## authorization.global.php

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
