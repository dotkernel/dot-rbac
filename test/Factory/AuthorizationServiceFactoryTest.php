<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Factory\AuthorizationServiceFactory;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthorizationServiceFactoryTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateService(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $rbac                   = $this->createMock(RbacInterface::class);
        $roleService            = $this->createMock(RoleServiceInterface::class);
        $assertionPluginManager = $this->createMock(AssertionPluginManager::class);
        $moduleOptions          = $this->createMock(AuthorizationOptions::class);

        $moduleOptions->method('getAssertions')->willReturn([
            'permissions' => ['test'],
            ['test1'],
        ]);

        $container->expects($this->any())->method('get')->willReturnMap(
            [
                [RbacInterface::class, $rbac],
                [RoleServiceInterface::class, $roleService],
                [AssertionPluginManager::class, $assertionPluginManager],
                [AuthorizationOptions::class, $moduleOptions],
            ],
        );

        $service = (new AuthorizationServiceFactory())($container);
        $this->assertInstanceOf(AuthorizationService::class, $service);
    }
}
