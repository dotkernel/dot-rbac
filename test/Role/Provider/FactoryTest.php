<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role\Provider;

use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use Dot\Rbac\Role\Provider\Factory;
use Dot\Rbac\Role\Provider\InMemoryRoleProvider;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\Role;
use DotTest\Rbac\TestData\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Factory::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(RoleProviderPluginManager::class)]
#[UsesClass(InvokableFactory::class)]
#[UsesClass(InMemoryRoleProvider::class)]
#[UsesClass(Role::class)]
class FactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory  = new Factory(new Container());
        $provider = $factory->create([
            'type'    => 'InMemory',
            'options' => ['roles' => ['admin' => ['permissions' => ['edit']]]],
        ]);

        $this->assertInstanceOf(InMemoryRoleProvider::class, $provider);
        $admin = $provider->getRole('admin');
        $this->assertInstanceOf(Role::class, $admin);
        $this->assertTrue($admin->hasPermission('edit'));
    }

    public function testGetRoleProviderPluginManager(): void
    {
        $manager = new RoleProviderPluginManager(new Container());

        $this->assertSame($manager, (new Factory(new Container(), $manager))->getRoleProviderPluginManager());

        $factory = new Factory(new Container());
        $this->assertSame($factory->getRoleProviderPluginManager(), $factory->getRoleProviderPluginManager());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, string}>
     */
    public static function invalidSpecsProvider(): iterable
    {
        yield 'no type' => [[], 'Empty or no role provider set in config'];
        yield 'empty type' => [['type' => ''], 'Empty or no role provider set in config'];
        yield 'array type' => [['type' => ['InMemory']], 'Invalid role provider type of type array'];
        yield 'string options' => [['type' => 'InMemory', 'options' => 'x'], 'Role provider options must be an array'];
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
