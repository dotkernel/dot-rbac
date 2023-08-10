<?php

declare(strict_types=1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Role\Provider\Factory;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RoleServiceFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName): RoleService
    {
        $authorizationOptions = $container->get(AuthorizationOptions::class);
        $identityProvider     = $container->get(IdentityProviderInterface::class);

        $roleProviderConfig = $authorizationOptions->getRoleProvider();
        if (empty($roleProviderConfig)) {
            throw new RuntimeException('No role provider was set for authorization');
        }

        $factory      = new Factory($container, $container->get(RoleProviderPluginManager::class));
        $roleProvider = $factory->create($roleProviderConfig);
        /** @var RoleService $service */
        $service = new $requestedName($identityProvider, $roleProvider);
        $service->setGuestRole($authorizationOptions->getGuestRole());

        return $service;
    }
}
