<?php

declare(strict_types=1);

namespace DotTest\Rbac\Assertion;

use Dot\Rbac\Assertion\AssertionInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Exception\InvalidArgumentException;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use DotTest\Rbac\TestData\AllowAssertion;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\IdentityProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AssertionPluginManager::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(InvokableFactory::class)]
class AssertionPluginManagerTest extends TestCase
{
    public function testBuildsAssertion(): void
    {
        $manager = new AssertionPluginManager(new Container(), [
            'factories' => [AllowAssertion::class => InvokableFactory::class],
        ]);

        $this->assertInstanceOf(AllowAssertion::class, $manager->build(AllowAssertion::class));
    }

    public function testRejectsOtherInstances(): void
    {
        $manager = new AssertionPluginManager(new Container(), [
            'factories' => [IdentityProvider::class => InvokableFactory::class],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            AssertionPluginManager::class . ' can only create instances of ' . AssertionInterface::class
        );
        $manager->build(IdentityProvider::class);
    }
}
