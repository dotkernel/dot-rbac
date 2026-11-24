<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Factory\RoleProviderPluginManagerFactory;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use DotTest\Rbac\TestData\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RoleProviderPluginManagerFactory::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(RoleProviderPluginManager::class)]
class RoleProviderPluginManagerFactoryTest extends TestCase
{
    public function testCanCreate(): void
    {
        $container = new Container([
            'config' => [
                'dot_authorization' => [
                    'role_provider_manager' => ['aliases' => ['memory' => 'InMemory']],
                ],
            ],
        ]);

        $manager = (new RoleProviderPluginManagerFactory())($container);
        $this->assertTrue($manager->has('InMemory'));
        $this->assertFalse($manager->has('unknown'));
    }
}
