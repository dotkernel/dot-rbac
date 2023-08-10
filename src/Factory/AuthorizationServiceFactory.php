<?php

declare(strict_types=1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function is_array;

class AuthorizationServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName): AuthorizationService
    {
        $rbac             = $container->get(RbacInterface::class);
        $roleService      = $container->get(RoleServiceInterface::class);
        $assertionFactory = new Factory($container, $container->get(AssertionPluginManager::class));

        /** @var AuthorizationService $service */
        $service = new $requestedName($rbac, $roleService, $assertionFactory);

        /** @var AuthorizationOptions $moduleOptions */
        $moduleOptions = $container->get(AuthorizationOptions::class);
        $assertions    = $moduleOptions->getAssertions();
        foreach ($assertions as $assertion) {
            if (is_array($assertion) && isset($assertion['permissions']) && is_array($assertion['permissions'])) {
                foreach ($assertion['permissions'] as $permission) {
                    $service->addAssertion($permission, $assertion);
                }
            }
        }

        return $service;
    }
}
