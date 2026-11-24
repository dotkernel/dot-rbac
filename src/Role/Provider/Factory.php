<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Dot\Rbac\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

use function get_debug_type;
use function is_array;
use function is_string;
use function sprintf;

class Factory
{
    public function __construct(
        protected ContainerInterface $container,
        protected ?RoleProviderPluginManager $roleProviderPluginManager = null
    ) {
    }

    /**
     * @param array<string, mixed> $specs
     */
    public function create(array $specs): RoleProviderInterface
    {
        $type = $specs['type'] ?? null;
        if ($type === null || $type === '') {
            throw new RuntimeException('Empty or no role provider set in config');
        }

        if (! is_string($type)) {
            throw new RuntimeException(sprintf('Invalid role provider type of type %s', get_debug_type($type)));
        }

        $options = $specs['options'] ?? null;
        if ($options !== null && ! is_array($options)) {
            throw new RuntimeException('Role provider options must be an array');
        }

        return $this->getRoleProviderPluginManager()->build($type, $options);
    }

    public function getRoleProviderPluginManager(): RoleProviderPluginManager
    {
        if ($this->roleProviderPluginManager === null) {
            $this->roleProviderPluginManager = new RoleProviderPluginManager($this->container, []);
        }

        return $this->roleProviderPluginManager;
    }
}
