<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role\Provider;

use Dot\Rbac\Exception\InvalidArgumentException;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use Dot\Rbac\Role\Provider\InMemoryRoleProvider;
use Dot\Rbac\Role\Provider\RoleProviderInterface;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\Role;
use DotTest\Rbac\TestData\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(RoleProviderPluginManager::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(InvokableFactory::class)]
#[UsesClass(InMemoryRoleProvider::class)]
#[UsesClass(Role::class)]
class RoleProviderPluginManagerTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function aliasProvider(): iterable
    {
        foreach (
            [
                'inmemoryroleprovider',
                'inMemoryRoleProvider',
                'InMemoryRoleProvider',
                'inmemory',
                'inMemory',
                'InMemory',
                InMemoryRoleProvider::class,
            ] as $alias
        ) {
            yield $alias => [$alias];
        }
    }

    #[DataProvider('aliasProvider')]
    public function testBuildsInMemoryRoleProvider(string $alias): void
    {
        $provider = (new RoleProviderPluginManager(new Container()))->build(
            $alias,
            ['roles' => ['admin' => ['permissions' => ['edit']]]]
        );

        $this->assertInstanceOf(InMemoryRoleProvider::class, $provider);
        $admin = $provider->getRole('admin');
        $this->assertInstanceOf(Role::class, $admin);
        $this->assertTrue($admin->hasPermission('edit'));
    }

    public function testWillNotCreateInvalidPlugin(): void
    {
        $manager = new RoleProviderPluginManager(new Container(), [
            'factories' => ['invalid' => static fn (): string => 'invalid'],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s can only create instances of %s; string is invalid',
                RoleProviderPluginManager::class,
                RoleProviderInterface::class
            )
        );
        $manager->build('invalid');
    }
}
