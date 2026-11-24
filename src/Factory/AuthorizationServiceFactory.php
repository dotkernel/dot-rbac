<?php

declare(strict_types=1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function is_array;
use function is_string;

class AuthorizationServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AuthorizationService
    {
        $rbac             = $container->get(RbacInterface::class);
        $roleService      = $container->get(RoleServiceInterface::class);
        $assertionFactory = new Factory($container, $container->get(AssertionPluginManager::class));

        $service = new AuthorizationService($rbac, $roleService, $assertionFactory);

        $moduleOptions = $container->get(AuthorizationOptions::class);
        if (! $moduleOptions instanceof AuthorizationOptions) {
            throw new RuntimeException('The service ' . AuthorizationOptions::class . ' has an invalid type');
        }

        foreach ($moduleOptions->getAssertions() as $assertion) {
            if (! is_array($assertion) || ! isset($assertion['permissions']) || ! is_array($assertion['permissions'])) {
                continue;
            }

            foreach ($assertion['permissions'] as $permission) {
                if (is_string($permission)) {
                    $service->addAssertion($permission, $assertion);
                }
            }
        }

        return $service;
    }
}
