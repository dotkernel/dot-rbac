<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Factory\AssertionPluginManagerFactory;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use DotTest\Rbac\TestData\AllowAssertion;
use DotTest\Rbac\TestData\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AssertionPluginManagerFactory::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(AssertionPluginManager::class)]
class AssertionPluginManagerFactoryTest extends TestCase
{
    public function testCanCreateManager(): void
    {
        $container = new Container([
            'config' => [
                'dot_authorization' => [
                    'assertion_manager' => [
                        'factories' => [AllowAssertion::class => InvokableFactory::class],
                    ],
                ],
            ],
        ]);

        $manager = (new AssertionPluginManagerFactory())($container);
        $this->assertTrue($manager->has(AllowAssertion::class));
    }

    public function testCanCreateManagerWithoutConfig(): void
    {
        $manager = (new AssertionPluginManagerFactory())(new Container(['config' => []]));

        $this->assertFalse($manager->has(AllowAssertion::class));
    }
}
