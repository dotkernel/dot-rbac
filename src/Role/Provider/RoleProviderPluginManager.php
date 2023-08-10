<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * @template-extends AbstractPluginManager<AbstractPluginManager>
 */
class RoleProviderPluginManager extends AbstractPluginManager
{
    /** @var string  */
    protected $instanceOf = RoleProviderInterface::class;

    /** @var string[]  */
    protected $factories = [
        InMemoryRoleProvider::class => InvokableFactory::class,
    ];

    /** @var string[]  */
    protected $aliases = [
        'inmemoryroleprovider' => InMemoryRoleProvider::class,
        'inMemoryRoleProvider' => InMemoryRoleProvider::class,
        'InMemoryRoleProvider' => InMemoryRoleProvider::class,
        'inmemory'             => InMemoryRoleProvider::class,
        'inMemory'             => InMemoryRoleProvider::class,
        'InMemory'             => InMemoryRoleProvider::class,
    ];
}
