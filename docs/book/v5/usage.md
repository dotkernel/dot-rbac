# Usage

To check if someone has access to an action, inject the `AuthorizationInterface::class` service into your class.
Then call the `isGranted` method.
There are two ways to call the `isGranted` method.

## First method

Specify the roles that you want to check.
A role can be a role name or an object that implements `Dot\Authorization\Role\RoleInterface`.

```php
$isGranted = $this->authorizationService->isGranted('edit', ['admin', 'user']);
```

## Second method

Do not specify the roles, or send an empty array as the second parameter.
The service then checks the roles of the authenticated identity.
If there is no authenticated identity, the service checks the guest role.

```php
$isGranted = $this->authorizationService->isGranted('edit');
```

## Context for assertions

The third parameter is the context.
The service sends the context to each assertion of the permission.

```php
$isGranted = $this->authorizationService->isGranted('edit', [], $article);
```
