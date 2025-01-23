# Customization

## Customize the IdentityProvider

Whenever you request an authorization check on the authenticated identity, the identity will be provided to the `AuthorizationService` through a registered `IdentityProviderInterface` service.

This is because identity is authentication dependent, so the module lets you overwrite this service, depending on your needs.
If you want to get the identity from other sources instead of the dot-authentication service, just overwrite the `IdentityProviderInterface::class` service in the service manager with your own implementation of this interface.

## Custom role providers

Write your own role provider by implementing the `RoleProviderInterface` and register it in the `RoleProviderPluginManager`.
After that, you can use them in the configuration file, as described above.

## Creating assertions

Assertions are checked after permission granting, right before returning the authorization result.
Assertions can have a last word in deciding if someone is authorized for the requested action.
A good assertion example could be an edit permission, but with the restriction that it should be able to edit the item just if the `user id` matches the item's `owner id`.
It is up to you to write the logic inside an assertion.

An assertion has to implement the `AssertionInterface` and be registered in the `AssertionPluginManager`.

This interface defines the following method

```php
public function assert(AuthorizationInterface $authorization, $context = null);
```

The context variable can be any external data that an assertion needs in order to decide the authorization status.
The assertion must return a boolean value, reflecting the assertion pass or failure status.
