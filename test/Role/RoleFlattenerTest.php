<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Role;
use Dot\Rbac\Role\RoleFlattener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function array_map;
use function iterator_to_array;

#[CoversClass(RoleFlattener::class)]
#[UsesClass(Role::class)]
#[UsesClass(HierarchicalRole::class)]
class RoleFlattenerTest extends TestCase
{
    public function testFlattenGivesChildrenAfterParent(): void
    {
        $guest = new Role('guest');
        $user  = new HierarchicalRole('user');
        $user->addChild($guest);
        $admin = new HierarchicalRole('admin');
        $admin->addChild($user);

        $this->assertSame(['admin', 'user', 'guest'], $this->names([$admin]));
    }

    public function testFlattenGivesSharedChildOneTime(): void
    {
        $guest = new Role('guest');
        $user  = new HierarchicalRole('user');
        $user->addChild($guest);

        $this->assertSame(['user', 'guest'], $this->names([$user, $guest]));
    }

    public function testFlattenStopsOnCycle(): void
    {
        $roleA = new HierarchicalRole('a');
        $roleB = new HierarchicalRole('b');
        $roleA->addChild($roleB);
        $roleB->addChild($roleA);

        $this->assertSame(['a', 'b'], $this->names([$roleA]));
    }

    /**
     * @param array<RoleInterface> $roles
     * @return list<string>
     */
    private function names(array $roles): array
    {
        return array_map(
            static fn (RoleInterface $role): string => $role->getName(),
            iterator_to_array(RoleFlattener::flatten($roles), false)
        );
    }
}
