<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;

/**
 * @extends AbstractPluginManager<RoleProviderInterface>
 */
class RoleProviderPluginManager extends AbstractPluginManager
{
    /** @var array<string, string|callable> */
    protected array $factories = [
        InMemoryRoleProvider::class => InvokableFactory::class,
    ];

    /** @var array<string, string> */
    protected array $aliases = [
        'inmemoryroleprovider' => InMemoryRoleProvider::class,
        'inMemoryRoleProvider' => InMemoryRoleProvider::class,
        'InMemoryRoleProvider' => InMemoryRoleProvider::class,
        'inmemory'             => InMemoryRoleProvider::class,
        'inMemory'             => InMemoryRoleProvider::class,
        'InMemory'             => InMemoryRoleProvider::class,
    ];

    protected function getInstanceOf(): string
    {
        return RoleProviderInterface::class;
    }
}
