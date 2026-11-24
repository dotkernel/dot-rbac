# Installation

Run the following command in your project root directory:

```shell
composer require dotkernel/dot-rbac
```

Version 5 requires PHP 8.3 or higher.

The package works with any PSR-11 container that accepts a factory for each service key, for example Laminas Service Manager.
The package does not install a container.
If your application uses Laminas Service Manager, require `laminas/laminas-servicemanager` in your application's `composer.json`.
