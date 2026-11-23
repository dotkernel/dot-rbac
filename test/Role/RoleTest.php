<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Rbac\Role\Role;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Role::class)]
class RoleTest extends TestCase
{
    public function testRoleAccessors(): void
    {
        $role = new Role('testRole');
        $this->assertSame('testRole', $role->getName());
        $this->assertSame([], $role->getPermissions());

        $role->addPermission('testPermission');
        $this->assertSame(['testPermission' => 'testPermission'], $role->getPermissions());
        $this->assertTrue($role->hasPermission('testPermission'));
        $this->assertFalse($role->hasPermission('noPermission'));
    }
}
