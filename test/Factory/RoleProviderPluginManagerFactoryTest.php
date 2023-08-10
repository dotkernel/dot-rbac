<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Factory\RoleProviderPluginManagerFactory;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RoleProviderPluginManagerFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCanCreate(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $config = [
            'dot_authorization' => [
                'role_provider_manager' => [],
            ],
        ];

        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $service = (new RoleProviderPluginManagerFactory())($container);
        $this->assertInstanceOf(RoleProviderPluginManager::class, $service);
    }
}
