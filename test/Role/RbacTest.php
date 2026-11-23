<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Rbac\Rbac;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Role;
use Dot\Rbac\Role\RoleFlattener;
use DotTest\Rbac\TestData\PlainRole;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Rbac::class)]
#[UsesClass(Role::class)]
#[UsesClass(HierarchicalRole::class)]
#[UsesClass(RoleFlattener::class)]
class RbacTest extends TestCase
{
    public function testIsGranted(): void
    {
        $rbac = new Rbac();
        $role = new Role('testRole');
        $this->assertFalse($rbac->isGranted('permission', [$role]));

        $role->addPermission('permission');
        $this->assertTrue($rbac->isGranted('permission', [$role]));
    }

    public function testIsGrantedWithoutRoles(): void
    {
        $this->assertFalse((new Rbac())->isGranted('permission', []));
    }

    public function testChildPermissionIsGranted(): void
    {
        $child = new Role('child');
        $child->addPermission('view');
        $parent = new HierarchicalRole('parent');
        $parent->addChild($child);

        $rbac = new Rbac();
        $this->assertTrue($rbac->isGranted('view', [$parent]));
        $this->assertFalse($rbac->isGranted('view', [new HierarchicalRole('other')]));
    }

    public function testCircularHierarchyTerminates(): void
    {
        $roleA = new HierarchicalRole('a');
        $roleB = new HierarchicalRole('b');
        $roleA->addChild($roleB);
        $roleB->addChild($roleA);

        $this->assertFalse((new Rbac())->isGranted('x', [$roleA]));

        $roleB->addPermission('x');
        $this->assertTrue((new Rbac())->isGranted('x', [$roleA]));
    }

    public function testRoleWithoutPermissionInterfaceIsNotGranted(): void
    {
        $this->assertFalse((new Rbac())->isGranted('x', [new PlainRole('plain')]));
    }
}
