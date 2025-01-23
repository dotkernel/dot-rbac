<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Factory\InvokableFactory;

use function gettype;
use function is_object;
use function sprintf;

/**
 * @template-extends AbstractPluginManager<AbstractPluginManager>
 */
class RoleProviderPluginManager extends AbstractPluginManager
{
    protected string $instanceOf = RoleProviderInterface::class;

    protected array $factories = [
        InMemoryRoleProvider::class => InvokableFactory::class,
    ];

    protected array $aliases = [
        'inmemoryroleprovider' => InMemoryRoleProvider::class,
        'inMemoryRoleProvider' => InMemoryRoleProvider::class,
        'InMemoryRoleProvider' => InMemoryRoleProvider::class,
        'inmemory'             => InMemoryRoleProvider::class,
        'inMemory'             => InMemoryRoleProvider::class,
        'InMemory'             => InMemoryRoleProvider::class,
    ];

    public function validate(mixed $instance): void
    {
        if (! $instance instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                static::class,
                $this->instanceOf,
                is_object($instance) ? $instance::class : gettype($instance)
            ));
        }
    }
}
