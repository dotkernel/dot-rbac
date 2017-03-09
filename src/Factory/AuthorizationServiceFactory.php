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

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;
use Interop\Container\ContainerInterface;

/**
 * Class AuthorizationServiceFactory
 * @package Dot\Rbac\Factory
 */
class AuthorizationServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return AuthorizationService
     */
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $rbac = $container->get(RbacInterface::class);
        $roleService = $container->get(RoleServiceInterface::class);
        $assertionFactory = new Factory($container, $container->get(AssertionPluginManager::class));

        /** @var AuthorizationService $service */
        $service = new $requestedName($rbac, $roleService, $assertionFactory);

        /** @var AuthorizationOptions $moduleOptions */
        $moduleOptions = $container->get(AuthorizationOptions::class);
        $assertions = $moduleOptions->getAssertions();
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
