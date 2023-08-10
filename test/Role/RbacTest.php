<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Rbac\Rbac;
use Dot\Rbac\Role\HierarchicalRole;
use PHPUnit\Framework\TestCase;

class RbacTest extends TestCase
{
    public function testIsGranted(): void
    {
        $rbac = new Rbac();

        $permissions = 'permission';

        $role = new HierarchicalRole('testRole');
        $this->assertFalse($rbac->isGranted($permissions, [$role]));
        $role->addPermission('permission');

        $this->assertTrue($rbac->isGranted($permissions, [$role]));
    }
}
