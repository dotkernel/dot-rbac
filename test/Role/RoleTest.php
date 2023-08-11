<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Rbac\Role\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testRoleAccessors(): void
    {
        $role = new Role('testRole');

        $this->assertSame('testRole', $role->getName());
        $role->addPermission('testPermission');
        $this->assertIsArray($role->getPermissions());
        $this->assertTrue($role->hasPermission('testPermission'));
        $this->assertFalse($role->hasPermission('noPermission'));
    }
}
