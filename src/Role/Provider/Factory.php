<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Dot\Rbac\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

class Factory
{
    protected ContainerInterface $container;

    public function __construct(
        ContainerInterface $container,
        protected ?RoleProviderPluginManager $roleProviderPluginManager = null
    ) {
        $this->container = $container;
    }

    public function create(array $specs): RoleProviderInterface
    {
        $type = $specs['type'] ?? '';
        if (empty($type)) {
            throw new RuntimeException('Empty or no role provider set in config');
        }

        $roleProviderManager = $this->getRoleProviderPluginManager();
        return $roleProviderManager->get($type, $specs['options'] ?? null);
    }

    public function getRoleProviderPluginManager(): RoleProviderPluginManager
    {
        if (! $this->roleProviderPluginManager) {
            $this->roleProviderPluginManager = new RoleProviderPluginManager($this->container, []);
        }

        return $this->roleProviderPluginManager;
    }
}
