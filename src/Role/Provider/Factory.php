<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Role\Provider;

use Dot\Rbac\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

/**
 * Class Factory
 * @package Dot\Rbac\Role\Provider
 */
class Factory
{
    /** @var  ContainerInterface */
    protected $container;

    /** @var RoleProviderPluginManager */
    protected $roleProviderPluginManager;

    /**
     * Factory constructor.
     * @param RoleProviderPluginManager $roleProviderPluginManager
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container,
        RoleProviderPluginManager $roleProviderPluginManager = null
    ) {
        $this->container = $container;
        $this->roleProviderPluginManager = $roleProviderPluginManager;
    }

    /**
     * @param array $specs
     * @return RoleProviderInterface
     */
    public function create(array $specs): RoleProviderInterface
    {
        $type = $specs['type'] ?? '';
        if (empty($type)) {
            throw new RuntimeException('Empty or no role provider set in config');
        }

        $roleProviderManager = $this->getRoleProviderPluginManager();
        return $roleProviderManager->get($type, $specs['options'] ?? null);
    }

    /**
     * @return RoleProviderPluginManager
     */
    public function getRoleProviderPluginManager(): RoleProviderPluginManager
    {
        if (!$this->roleProviderPluginManager) {
            $this->roleProviderPluginManager = new RoleProviderPluginManager($this->container, []);
        }

        return $this->roleProviderPluginManager;
    }
}
