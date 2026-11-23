# Upgrading

This page describes the changes that can break your code when you upgrade to version 5.

## From 4.x to 5.x

| Change | Symptom | Fix |
| --- | --- | --- |
| PHP 8.2 is not supported | Composer does not install 5.x | Use PHP 8.3 or higher |
| `laminas/laminas-servicemanager` is not a requirement | Class `Laminas\ServiceManager\...` not found, if your application uses it but does not require it | Add `laminas/laminas-servicemanager` to your application's `composer.json` |
| The plugin managers do not extend the Laminas `AbstractPluginManager` | Call to undefined method `get()`, `configure()` or `setFactory()` | Use `build()`, and the `factories` and `aliases` configuration |
| `InvalidServiceException` is not thrown | A `catch` block does not run | Catch `Dot\Rbac\Exception\InvalidArgumentException` or `Dot\Rbac\Exception\ExceptionInterface` |
| The plugin manager keys `invokables`, `abstract_factories`, `delegators` and `shared` are ignored | `Dot\Rbac\Exception\InvalidArgumentException`: "No factory is registered for plugin" | Register each plugin under `factories`, for example with `Dot\Rbac\Plugin\InvokableFactory` |
| `RoleServiceInterface::resolveRoles()` is new | Fatal error in your own implementation of the interface | Add the method |
| `RoleService::convertRoles()` is now the public method `resolveRoles()` | Call to undefined method `convertRoles()` in a subclass | Call `resolveRoles()` |
| `Rbac` checks permissions only on `PermissionRoleInterface` roles | Your own role class gets no permissions | Implement `Dot\Rbac\Role\PermissionRoleInterface` or extend `Dot\Rbac\Role\Role` |
| `AuthorizationService::addAssertion()` has a native parameter type | `TypeError` instead of `RuntimeException` for a wrong type | Send an `AssertionInterface` or a non-empty array |
| An empty assertion array is rejected | `Dot\Rbac\Exception\InvalidArgumentException` | Remove the empty assertion |
| An identity that is not `null` must have roles | `Dot\Rbac\Exception\RuntimeException`: "The identity must implement" | Return `null` for a guest. Return an `IdentityInterface` for an authenticated user |
| A circular role hierarchy throws an exception | `Dot\Rbac\Exception\RuntimeException` instead of a fatal error | Remove the cycle from the configuration |

### Replacing the Laminas plugin manager features

Version 4 plugin managers extend `Laminas\ServiceManager\AbstractPluginManager`.
Version 5 plugin managers extend `Dot\Rbac\Plugin\AbstractPluginManager`, which accepts only `factories` and `aliases`.

Before, with Laminas `InvokableFactory` and `invokables`:

```php
'assertion_manager' => [
    'invokables' => [
        EditAssertion::class => EditAssertion::class,
    ],
    'factories'  => [
        OwnerAssertion::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
    ],
],
```

After:

```php
'assertion_manager' => [
    'factories' => [
        EditAssertion::class  => \Dot\Rbac\Plugin\InvokableFactory::class,
        OwnerAssertion::class => \Dot\Rbac\Plugin\InvokableFactory::class,
    ],
],
```

A factory class with the signature `__invoke(ContainerInterface $container, string $requestedName, ?array $options = null)` continues to work.
The Laminas `InvokableFactory` also continues to work if your application requires `laminas/laminas-servicemanager`.

Before, with a direct call to the plugin manager:

```php
$assertion = $container->get(AssertionPluginManager::class)->get(EditAssertion::class);
```

After:

```php
$assertion = $container->get(AssertionPluginManager::class)->build(EditAssertion::class);
```

## From 3.x to 5.x

Apply the changes from 4.x to 5.x.
Version 4.0.0 also changed `laminas/laminas-servicemanager` from 3.x to 4.x.
If your application uses Laminas Service Manager, upgrade it to 4.x.
