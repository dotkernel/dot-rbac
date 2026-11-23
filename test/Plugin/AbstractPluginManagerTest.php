<?php

declare(strict_types=1);

namespace DotTest\Rbac\Plugin;

use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Exception\InvalidArgumentException;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use DotTest\Rbac\TestData\AllowAssertion;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\ContextAssertion;
use DotTest\Rbac\TestData\DenyAssertion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use stdClass;

#[CoversClass(AbstractPluginManager::class)]
#[UsesClass(AssertionPluginManager::class)]
#[UsesClass(InvokableFactory::class)]
class AbstractPluginManagerTest extends TestCase
{
    public function testBuildWithFactoryClass(): void
    {
        $manager = new AssertionPluginManager(new Container(), [
            'factories' => [AllowAssertion::class => InvokableFactory::class],
        ]);

        $this->assertTrue($manager->has(AllowAssertion::class));
        $first = $manager->build(AllowAssertion::class);
        $this->assertInstanceOf(AllowAssertion::class, $first);
        $this->assertNotSame($first, $manager->build(AllowAssertion::class));
    }

    public function testBuildWithAliasAndCallableFactory(): void
    {
        $container = new Container(['deny' => new DenyAssertion()]);
        $manager   = new AssertionPluginManager($container, [
            'aliases'   => ['deny-alias' => 'deny'],
            'factories' => [
                'deny' => static function (ContainerInterface $container, string $requestedName): AssertionInterface {
                    $assertion = $container->get($requestedName);
                    self::assertInstanceOf(AssertionInterface::class, $assertion);

                    return $assertion;
                },
            ],
        ]);

        $this->assertTrue($manager->has('deny-alias'));
        $this->assertInstanceOf(DenyAssertion::class, $manager->build('deny-alias'));
    }

    public function testFactoryGetsOptions(): void
    {
        $manager = new AssertionPluginManager(new Container(), [
            'factories' => [ContextAssertion::class => InvokableFactory::class],
        ]);

        $assertion = $manager->build(ContextAssertion::class, ['allowed' => 'mine']);
        $this->assertInstanceOf(ContextAssertion::class, $assertion);
    }

    public function testUnknownPluginThrowsException(): void
    {
        $manager = new AssertionPluginManager(new Container());

        $this->assertFalse($manager->has('unknown'));
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'No factory is registered for plugin "unknown" in ' . AssertionPluginManager::class
        );
        $manager->build('unknown');
    }

    public function testFactoryThatIsNotCallableThrowsException(): void
    {
        $manager = new AssertionPluginManager(new Container(), [
            'factories' => [AllowAssertion::class => 'NotAClass\\Factory'],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The factory for plugin "' . AllowAssertion::class . '" is not callable');
        $manager->build(AllowAssertion::class);
    }

    public function testWrongInstanceThrowsException(): void
    {
        $manager = new AssertionPluginManager(new Container(), [
            'factories' => ['bad' => static fn (): stdClass => new stdClass()],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            AssertionPluginManager::class . ' can only create instances of '
            . AssertionInterface::class . '; stdClass is invalid'
        );
        $manager->build('bad');
    }

    public function testBuildRejectsString(): void
    {
        $manager = new AssertionPluginManager(new Container(), [
            'factories' => ['text' => static fn (): string => 'test'],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('; string is invalid');
        $manager->build('text');
    }
}
