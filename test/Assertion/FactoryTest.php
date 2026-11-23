<?php

declare(strict_types=1);

namespace DotTest\Rbac\Assertion;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use DotTest\Rbac\TestData\AllowAssertion;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\ContextAssertion;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Factory::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(AssertionPluginManager::class)]
#[UsesClass(InvokableFactory::class)]
class FactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $manager = new AssertionPluginManager(new Container(), [
            'factories' => [
                AllowAssertion::class   => InvokableFactory::class,
                ContextAssertion::class => InvokableFactory::class,
            ],
        ]);
        $subject = new Factory(new Container(), $manager);

        $this->assertInstanceOf(AllowAssertion::class, $subject->create(['type' => AllowAssertion::class]));
        $this->assertInstanceOf(
            ContextAssertion::class,
            $subject->create(['type' => ContextAssertion::class, 'options' => ['allowed' => 'mine']])
        );
    }

    public function testGetAssertionPluginManager(): void
    {
        $manager = new AssertionPluginManager(new Container());
        $this->assertSame($manager, (new Factory(new Container(), $manager))->getAssertionPluginManager());

        $subject = new Factory(new Container());
        $this->assertSame($subject->getAssertionPluginManager(), $subject->getAssertionPluginManager());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, string}>
     */
    public static function invalidSpecsProvider(): iterable
    {
        yield 'no type' => [[], 'Invalid assertion type of type null'];
        yield 'empty type' => [['type' => ''], 'Invalid assertion type of type string'];
        yield 'array type' => [['type' => ['x']], 'Invalid assertion type of type array'];
        yield 'string options' => [
            ['type' => AllowAssertion::class, 'options' => 'x'],
            'Assertion options must be an array',
        ];
    }

    /**
     * @param array<string, mixed> $specs
     */
    #[DataProvider('invalidSpecsProvider')]
    public function testCreateWithInvalidSpecsThrowsException(array $specs, string $message): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($message);
        (new Factory(new Container()))->create($specs);
    }
}
