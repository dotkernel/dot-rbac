<?php

declare(strict_types=1);

namespace DotTest\Rbac\Assertion;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Exception\RuntimeException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class FactoryTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function testCreateRuntimeException(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $subject   = new Factory($container);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid assertion type');
        $subject->create([]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function testCreate(): void
    {
        $container              = $this->createMock(ContainerInterface::class);
        $assertionPluginManager = $this->createMock(AssertionPluginManager::class);

        $assertionPluginManager->expects($this->once())
            ->method('build')
            ->with('testType', null)
            ->willReturn(new class implements AssertionInterface {
                public function assert(AuthorizationInterface $authorization, mixed $context = null): bool
                {
                    return true;
                }
            });
        $subject = new Factory($container, $assertionPluginManager);

        $result = $subject->create(['type' => 'testType']);
        $this->assertInstanceOf(AssertionInterface::class, $result);
    }

    /**
     * @throws Exception
     */
    public function testGetAssertionPluginManager(): void
    {
        $container              = $this->createMock(ContainerInterface::class);
        $assertionPluginManager = $this->createMock(AssertionPluginManager::class);

        $subject = new Factory($container, $assertionPluginManager);

        $result = $subject->getAssertionPluginManager();
        $this->assertInstanceOf(AssertionPluginManager::class, $result);
    }
}
