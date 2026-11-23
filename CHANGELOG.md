# Changelog

The dates of releases 2.0.0 to 4.2.1 are the dates of the tagged commits.

## 5.0.0 - TBD

### Changed

* The package works with any PSR-11 container. `AssertionPluginManager` and `RoleProviderPluginManager` extend `Dot\Rbac\Plugin\AbstractPluginManager` instead of `Laminas\ServiceManager\AbstractPluginManager`.
* `isGranted()` accepts role names and role objects.
* `RoleServiceInterface` has the new method `resolveRoles()`. It replaces the protected method `RoleService::convertRoles()`.
* `Rbac` checks permissions only on roles that implement `PermissionRoleInterface`.
* `AuthorizationService::addAssertion()` accepts only `AssertionInterface` or a non-empty array.
* `RoleService` throws a `RuntimeException` for an identity that is not `null` and has no `getRoles()` method.
* PHPStan level 8.
* PHPUnit 12, with fixtures instead of mocks.

### Added

* PHP 8.6 support.
* `Dot\Rbac\Plugin\AbstractPluginManager` and `Dot\Rbac\Plugin\InvokableFactory`.
* `Dot\Rbac\Role\PermissionRoleInterface`.
* `Dot\Rbac\Role\RoleFlattener`.
* `Dot\Rbac\Factory\RbacFactory`.
* `psr/container` as a direct requirement.
* Documentation for version 5 and an upgrade guide.

### Deprecated

* Nothing

### Removed

* PHP 8.2 support.
* `laminas/laminas-servicemanager` requirement.
* Plugin manager support for `invokables`, `abstract_factories`, `delegators` and `shared`.

### Fixed

* Infinite recursion on a circular role hierarchy.
* `Error` when `isGranted()` gets role names.
* `TypeError` or `Error` for integer roles and roles named like a class.
* `Error` for an identity without `getRoles()`.
* `Error` for an empty assertion specification.
* `TypeError` for an assertion or role provider type that is not a string.
* Syntax error in the hierarchical configuration example.

## 4.2.1 - 2025-10-31

### Changed

* Nothing

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* PHP version badge in `SECURITY.md` and the v3 documentation (#38).

## 4.2.0 - 2025-10-31

### Changed

* Nothing

### Added

* PHP 8.4 and 8.5 support (#34).

### Deprecated

* Nothing

### Removed

* PHP 8.1 support (#34).

### Fixed

* Nothing

## 4.1.0 - 2025-03-14

### Changed

* PHPStan replaces Psalm (#32).

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Psalm (#32).

### Fixed

* Nothing

## 4.0.1 - 2025-01-28

### Changed

* Psalm 6.x (#28).

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 4.0.0 - 2025-01-23

### Changed

* `laminas/laminas-servicemanager` 4.x (#26).

### Added

* `RoleProviderPluginManager` test.

### Deprecated

* Nothing

### Removed

* `laminas/laminas-servicemanager` 3.x support (#26).

### Fixed

* Nothing

## 3.8.0 - 2025-10-31

### Changed

* Nothing

### Added

* PHP 8.4 and 8.5 support (#36).

### Deprecated

* Nothing

### Removed

* PHP 8.1 support (#36).

### Fixed

* Nothing

## 3.7.0 - 2025-03-11

### Changed

* PHPStan replaces Psalm (#30).

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Psalm (#30).

### Fixed

* Nothing

## 3.6.0 - 2024-05-03

### Changed

* Code review.
* Documentation, workflows and `SECURITY.md`.

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 3.5.2 - 2023-11-29

### Changed

* Nothing

### Added

* PHP 8.3 support.

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 3.5.1 - 2023-08-24

### Changed

* Nothing

### Added

* Codecov.

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 3.5.0 - 2023-08-11

### Changed

* Return types on methods.
* README.

### Added

* Psalm, PHPUnit, Laminas coding standard and tests.

### Deprecated

* Nothing

### Removed

* PHP 7.4 and 8.0 support.
* Unnecessary code.

### Fixed

* Nothing

## 3.4.0 - 2023-03-29

### Changed

* Nothing

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* `laminas/laminas-dependency-plugin`.

### Fixed

* Nothing

## 3.3.0 - 2022-04-29

### Changed

* Dependencies.

### Added

* README badges.

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 3.2.0 - 2020-11-03

### Changed

* Composer 2 compatibility.
* Update composer.json to require version 2 of laminas/laminas-dependency-plugin to be Composer 2 compatible.

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* bump min version of laminas/laminas-dependency-plugin
* bump min version of dotkernel/dot-authorization

## 3.1.1 - 2020-05-25

### Changed

* Nothing

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* An unspecified issue (commit "fix some issue").

## 3.1.0 - 2020-05-15

### Changed

* Compatibility with the 3.0 packages.

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 3.0.1 - 2020-05-14

### Changed

* `composer.json`.

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 3.0.0 - 2020-05-14

### Changed

* Zend to Laminas, Expressive to Mezzio.
* PHP 7.4 or higher.

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 2.9.2 - 2024-03-27

### Changed

* Nothing

### Added

* PHP 8.3 support (2.x line).

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 2.9.1 - 2023-08-23

### Changed

* Composer dependencies (2.x line).

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 2.9.0 - 2023-02-13

### Changed

* PHP 8 support and Composer cleanup (2.x line).

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 2.0.0 - 2020-01-30

### Changed

* Zend to Laminas, Expressive to Mezzio.

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 0.2.1 - 2018-05-08

### Changed

* updated dependencies

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 0.2.0 - 2017-03-17

### Changed

* updated factories to use PSR11 container

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 0.1.1 - 2017-03-11

Updated php file headers doc blocks

### Added

* Nothing

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing

## 0.1.0 - 2017-03-09

Initial tagged release

### Added

* Everything

### Deprecated

* Nothing

### Removed

* Nothing

### Fixed

* Nothing
