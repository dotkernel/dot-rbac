<?php

declare(strict_types=1);

namespace Dot\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;
use Generator;

use function spl_object_id;

/**
 * Gives each role and each child role one time, also when the hierarchy has a cycle.
 */
final class RoleFlattener
{
    /**
     * @param array<RoleInterface> $roles
     * @return Generator<int, RoleInterface>
     */
    public static function flatten(array $roles): Generator
    {
        $visited = [];

        return self::walk($roles, $visited);
    }

    /**
     * @param array<RoleInterface> $roles
     * @param array<int, true> $visited
     * @return Generator<int, RoleInterface>
     */
    private static function walk(array $roles, array &$visited): Generator
    {
        foreach ($roles as $role) {
            $id = spl_object_id($role);
            if (isset($visited[$id])) {
                continue;
            }

            $visited[$id] = true;
            yield $role;

            if ($role instanceof HierarchicalRoleInterface) {
                foreach (self::walk($role->getChildren(), $visited) as $child) {
                    yield $child;
                }
            }
        }
    }
}
