<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

declare(strict_types = 1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Role\Provider\Factory;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleService;
use Interop\Container\ContainerInterface;

/**
 * Class RoleServiceFactory
 * @package Dot\Rbac\Factory
 */
class RoleServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return RoleService
     */
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $authorizationOptions = $container->get(AuthorizationOptions::class);
        $identityProvider = $container->get(IdentityProviderInterface::class);

        $roleProviderConfig = $authorizationOptions->getRoleProvider();
        if (empty($roleProviderConfig)) {
            throw new RuntimeException('No role provider was set for authorization');
        }

        $factory = new Factory($container, $container->get(RoleProviderPluginManager::class));
        $roleProvider = $factory->create($roleProviderConfig);

        /** @var RoleService $service */
        $service = new $requestedName($identityProvider, $roleProvider);
        $service->setGuestRole($authorizationOptions->getGuestRole());

        return $service;
    }
}
