<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Factory\RoleServiceFactory;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Role\RoleService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class RoleServiceFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateService(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $authorizationOptions = $this->createMock(AuthorizationOptions::class);
        $identityProvider     = $this->createMock(IdentityProviderInterface::class);
        $authorizationOptions->method('getRoleProvider')->willReturn(
            [
                'type'   => 'inmemoryroleprovider',
                'option' => ['testOptions'],
            ]
        );

        $container->expects($this->any())->method('get')->willReturnMap(
            [
                [AuthorizationOptions::class, $authorizationOptions],
                [IdentityProviderInterface::class, $identityProvider],
            ]
        );

        $service = (new RoleServiceFactory())($container, RoleService::class);

        $this->assertInstanceOf(RoleService::class, $service);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateService(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $authorizationOptions = $this->createMock(AuthorizationOptions::class);
        $identityProvider     = $this->createMock(IdentityProviderInterface::class);
        $this->expectException(RuntimeException::class);
        $authorizationOptions->method('getRoleProvider')->willReturn([]);

        $container->expects($this->any())->method('get')->willReturnMap(
            [
                [AuthorizationOptions::class, $authorizationOptions],
                [IdentityProviderInterface::class, $identityProvider],
            ]
        );

        (new RoleServiceFactory())($container, RoleService::class);
    }
}
