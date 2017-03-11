<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Role\Provider;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * Class RoleProviderPluginManager
 * @package Dot\Rbac\Role
 */
class RoleProviderPluginManager extends AbstractPluginManager
{
    protected $instanceOf = RoleProviderInterface::class;

    protected $factories = [
        InMemoryRoleProvider::class => InvokableFactory::class,
    ];

    protected $aliases = [
        'inmemoryroleprovider' => InMemoryRoleProvider::class,
        'inMemoryRoleProvider' => InMemoryRoleProvider::class,
        'InMemoryRoleProvider' => InMemoryRoleProvider::class,
        'inmemory' => InMemoryRoleProvider::class,
        'inMemory' => InMemoryRoleProvider::class,
        'InMemory' => InMemoryRoleProvider::class,
    ];
}
