<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Role;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HierarchicalRole::class)]
#[UsesClass(Role::class)]
class HierarchicalRoleTest extends TestCase
{
    public function testAccessors(): void
    {
        $hierarchicalRole = new HierarchicalRole('parent');
        $this->assertFalse($hierarchicalRole->hasChildren());
        $this->assertSame([], $hierarchicalRole->getChildren());

        $child = new Role('child');
        $hierarchicalRole->addChild($child);
        $this->assertTrue($hierarchicalRole->hasChildren());
        $this->assertSame(['child' => $child], $hierarchicalRole->getChildren());
    }
}
