<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role\Provider;

use Dot\Rbac\Factory\RoleProviderPluginManagerFactory;
use Dot\Rbac\Role\Provider\RoleProviderInterface;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function sprintf;

class RoleProviderPluginManagerTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testWillNotCreateInvalidPlugin(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn(['dot_authorization' => ['role_provider_manager' => []]]);

        $this->expectException(InvalidServiceException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s can only create instances of %s; string is invalid',
                RoleProviderPluginManager::class,
                RoleProviderInterface::class
            )
        );

        $service = (new RoleProviderPluginManagerFactory())($container);
        $service->validate('invalid');
    }
}
