<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Role\HierarchicalRole;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class HierarchicalRoleTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAccessors(): void
    {
        $name = 'testName';

        $hierarchicalRole = new HierarchicalRole($name);

        $this->assertFalse($hierarchicalRole->hasChildren());
        $child = $this->createMock(RoleInterface::class);
        $hierarchicalRole->addChild($child);
        $this->assertTrue($hierarchicalRole->hasChildren());
        $this->assertIsArray($hierarchicalRole->getChildren());
    }
}
