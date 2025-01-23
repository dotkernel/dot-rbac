# Usage

Whenever you need to check if someone is authorized to take some actions, inject the `AuthorizationInterface::class` service into your class, then call the `isGranted` method with the correct parameters.
There are 2 ways to call the isGranted method.

## First method

Specify which roles you want to check.

```php
$isGranted = $this->authorizationService->isGranted($permission, $roles);
```

## Second method

Do not specify the roles or send an empty array as the second parameter. This will check if the authenticated identity has permission.

```php
$isGranted = $this->authorizationService->isGranted($permission);
```
