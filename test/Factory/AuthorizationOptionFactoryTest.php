<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Factory\AuthorizationOptionsFactory;
use Dot\Rbac\Options\AuthorizationOptions;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthorizationOptionFactoryTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function testCanCreateInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn(['dot_authorization' => null]);

        $interface = (new AuthorizationOptionsFactory())($container);
        $this->assertInstanceOf(AuthorizationOptions::class, $interface);
    }
}
