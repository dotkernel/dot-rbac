<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role\Provider;

use Dot\Rbac\Role\Provider\InMemoryRoleProvider;
use PHPUnit\Framework\TestCase;

class InMemoryRoleProviderTest extends TestCase
{
    public function testGetRoles(): void
    {
        $memoryRoleProvide = new InMemoryRoleProvider([
            'roles' => [
                'testRole' => [
                    'children'    => ['testRoleChild1', 'testRoleChild2'],
                    'permissions' => ['*'],
                ],
            ],
        ]);

        $roles = ['roles', 'testRole'];

        $roles = $memoryRoleProvide->getRoles($roles);
        $this->assertCount(2, $roles);
    }
}
