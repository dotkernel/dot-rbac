<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role\Provider;

use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Provider\InMemoryRoleProvider;
use Dot\Rbac\Role\Role;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InMemoryRoleProvider::class)]
#[UsesClass(Role::class)]
#[UsesClass(HierarchicalRole::class)]
class InMemoryRoleProviderTest extends TestCase
{
    public function testGetRolesBuildsConfiguredRoles(): void
    {
        $provider = new InMemoryRoleProvider([
            'roles' => [
                'admin' => [
                    'permissions' => ['edit', 'delete'],
                ],
            ],
        ]);

        $roles = $provider->getRoles(['admin', 'unknown']);
        $this->assertCount(2, $roles);

        $admin = $roles[0];
        $this->assertInstanceOf(Role::class, $admin);
        $this->assertNotInstanceOf(HierarchicalRole::class, $admin);
        $this->assertSame('admin', $admin->getName());
        $this->assertTrue($admin->hasPermission('edit'));
        $this->assertTrue($admin->hasPermission('delete'));

        $unknown = $roles[1];
        $this->assertInstanceOf(Role::class, $unknown);
        $this->assertSame('unknown', $unknown->getName());
        $this->assertSame([], $unknown->getPermissions());
    }

    public function testHierarchicalRoleInheritsChildren(): void
    {
        $provider = new InMemoryRoleProvider([
            'roles' => [
                'admin' => ['children' => ['user'], 'permissions' => ['delete']],
                'user'  => ['children' => 'guest', 'permissions' => ['edit']],
                'guest' => ['permissions' => ['view']],
            ],
        ]);

        $admin = $provider->getRole('admin');
        $this->assertInstanceOf(HierarchicalRole::class, $admin);
        $this->assertTrue($admin->hasPermission('delete'));

        $user = $admin->getChildren()['user'];
        $this->assertInstanceOf(HierarchicalRole::class, $user);
        $this->assertTrue($user->hasPermission('edit'));
        $this->assertArrayHasKey('guest', $user->getChildren());
    }

    public function testRoleIsCached(): void
    {
        $provider = new InMemoryRoleProvider(['roles' => ['admin' => ['permissions' => ['edit']]]]);

        $this->assertSame($provider->getRole('admin'), $provider->getRole('admin'));
        $this->assertSame($provider->getRole('unknown'), $provider->getRole('unknown'));
    }

    public function testWithoutOptions(): void
    {
        $provider = new InMemoryRoleProvider();

        $this->assertSame([], $provider->getRoles([]));
        $this->assertSame('guest', $provider->getRole('guest')->getName());
    }

    public function testCircularHierarchyThrowsException(): void
    {
        $provider = new InMemoryRoleProvider([
            'roles' => [
                'a' => ['children' => ['b']],
                'b' => ['children' => ['a']],
            ],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Circular role hierarchy found at role "a"');
        $provider->getRoles(['a']);
    }

    public function testSelfReferenceThrowsException(): void
    {
        $provider = new InMemoryRoleProvider(['roles' => ['a' => ['children' => ['a']]]]);

        $this->expectException(RuntimeException::class);
        $provider->getRole('a');
    }
}
