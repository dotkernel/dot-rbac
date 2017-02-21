<?php
/**
 * @copyright: DotKernel
 * @library: dot-rbac
 * @author: n3vrax
 * Date: 2/1/2017
 * Time: 12:11 AM
 */

declare(strict_types = 1);

namespace Dot\Rbac\Role\Provider;

use Dot\Rbac\Exception\RuntimeException;
use Interop\Container\ContainerInterface;

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
        return $roleProviderManager->get($type, $specs['options'] ?? []);
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
