# dot-rbac
Rbac authorization model implementing [dot-authorization](https://github.com/dotkernel/dot-authorization)'s `AuthorizationInterface`. An authorization service is responsible for deciding if the authenticated identity or guest has access to certain parts of the application.
The RBAC model defines roles that can be assigned to users. The authorization is done on a role basis, not user basis as in ACL.
Each role can have one or multiple permissions/privileges assigned. When deciding if a user is authorized, the requested permission is checked in all user roles and if at least one role has that permission, access is granted.

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
'dependencies' => [
    //maybe you want to change the place identity is retrieved
    //change this line and add you own IdentityProvider
    //default is AuthenticationIdentityProvider which gets the identity from a dot-authentication service.
    //retrieved identities must implement Dot\Authorization\Identity\IdentityInterface
    //IdentityProviderInterface::class => \Your\Identity\Provider,
],

//all authorization config goes under this key
'dot_authorization' => [
    'assertion_map' => [
        //map permissions to assertions
        //'edit' => EditAssertion::class,
    ],

    //assertions are additional verifications during the authorization process
    //they can further restrict user access based on custom conditions, even if the role has that permission
    //before using assertions, you must register the in the AssertionPluginManager below
    'assertion_manager' => [
        'factories' => [
            //factory initialized assertions
        ],
        'invokables' => [
            //invokables assertions
            //EditAssertion::class => EditAssertion::class,
        ],
    ],

    //name of the guest role to use if no identity is provided
    'guest_role' => 'guest',

    //roles can come from various sources. We already provide an InMemoryRoleProvider that constructs the role list based on an array in the config file
    //custom role provider can be created, for example to fetch roles from different backends. Register them here
    'role_provider_manager' => [
        factories => [
            //your custom role provider definition
        ]
    ],

    //example for a flat RBAC model using the InMemoryRoleProvider
    'role_provider' => [
        //the key is the name of the role provider, as registered in the RoleProviderPluginManager above
        InMemoryRoleProvider::class => [
            'admin' => [
                'permissions' => [
                    'edit',
                    'delete',
                    'view',
                    'create'
                ]
            ],
            'user' => [
                'permissions' => [
                    'edit',
                    'view',
                    'create'
                ]
            ],
            'guest' => [
                'permissions' => ['view']
            ]
        ]
    ],

    //example for a hierarchical model, less to write but it can be confusing sometimes
    /*'role_provider' => [
        InMemoryRoleProvider::class => [
            //admin inherits the permissions from its child `user`
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
    ]*/
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