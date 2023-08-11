<?php

declare(strict_types=1);

namespace DotTest\Rbac\Assertion;

use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class AssertionPluginManagerTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function testInstanceOf(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $plugin    = $this->createMock(AssertionInterface::class);

        $this->expectNotToPerformAssertions();

        $assertionPluginManager = new AssertionPluginManager($container);
        $assertionPluginManager->validate($plugin);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function testInvalidInstanceOf(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $this->expectException(InvalidServiceException::class);

        $assertionPluginManager = new AssertionPluginManager($container);
        $assertionPluginManager->validate('test');
    }
}
