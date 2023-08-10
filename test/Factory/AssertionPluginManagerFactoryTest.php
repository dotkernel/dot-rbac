<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Factory\AssertionPluginManagerFactory;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AssertionPluginManagerFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCanCreateManager(): void
    {
        $config    = [
            'dot_authorization' => [
                'assertion_manager' => [],
            ],
        ];
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $result = (new AssertionPluginManagerFactory())($container);
        $this->assertInstanceOf(AssertionPluginManager::class, $result);
    }
}
