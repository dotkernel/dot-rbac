<?php

declare(strict_types=1);

namespace Dot\Rbac\Role\Provider;

use Dot\Rbac\Exception\RuntimeException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class Factory
{
    public function __construct(
        protected ContainerInterface $container,
        protected ?RoleProviderPluginManager $roleProviderPluginManager = null
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function create(array $specs): RoleProviderInterface
    {
        $type = $specs['type'] ?? '';
        if (empty($type)) {
            throw new RuntimeException('Empty or no role provider set in config');
        }

        $roleProviderManager = $this->getRoleProviderPluginManager();
        return $roleProviderManager->build($type, $specs['options'] ?? null);
    }

    public function getRoleProviderPluginManager(): RoleProviderPluginManager
    {
        if (! $this->roleProviderPluginManager) {
            $this->roleProviderPluginManager = new RoleProviderPluginManager($this->container, []);
        }

        return $this->roleProviderPluginManager;
    }
}
