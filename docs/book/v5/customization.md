# Customization

## Customize the IdentityProvider

When you check the authorization of the authenticated identity, the `AuthorizationService` gets the identity from the `IdentityProviderInterface` service.

The identity depends on the authentication, so you can replace this service.
The default implementation gets the identity from `Laminas\Authentication\AuthenticationService`.
To get the identity from a different source, register your own implementation of `IdentityProviderInterface` in your container, under the key `Dot\Rbac\Identity\IdentityProviderInterface`.

The identity must implement `Dot\Authorization\Identity\IdentityInterface`.
If the identity is `null`, the service uses the guest role.

## Custom role providers

To write your own role provider, implement `Dot\Rbac\Role\Provider\RoleProviderInterface`.
Then register a factory for it in the `role_provider_manager` configuration.

```php
'role_provider_manager' => [
    'factories' => [
        DatabaseRoleProvider::class => DatabaseRoleProviderFactory::class,
    ],
    'aliases'   => [
        'Database' => DatabaseRoleProvider::class,
    ],
],
'role_provider' => [
    'type'    => 'Database',
    'options' => [],
],
```

The factory gets the container, the plugin name and the `options`.

```php
public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): DatabaseRoleProvider
```

## Custom roles

The `Rbac` service checks permissions only on roles that implement `Dot\Rbac\Role\PermissionRoleInterface`.
`Dot\Rbac\Role\Role` and `Dot\Rbac\Role\HierarchicalRole` implement it.
If you write your own role class, extend `Role` or implement `PermissionRoleInterface`.
To give a role child roles, implement `Dot\Rbac\Role\HierarchicalRoleInterface`.

## Creating assertions

The service checks the assertions after it finds the permission, before it returns the result.
An assertion can deny access that the role permissions allow.
For example, an assertion can allow the `edit` permission only when the user id is the same as the owner id of the item.
You write the logic of the assertion.

An assertion implements `Dot\Rbac\Assertion\AssertionInterface`.
Register a factory for it in the `assertion_manager` configuration, then add it to `assertions`.
See [Configuration](configuration.md).

The interface defines this method:

```php
public function assert(AuthorizationInterface $authorization, mixed $context = null): bool;
```

The `$context` parameter is the third parameter of `isGranted`.
It can be any data that the assertion needs to make the decision.
The assertion returns `true` to allow access and `false` to deny access.

You can also add an assertion in code:

```php
$authorizationService->addAssertion('edit', new EditAssertion());
```
