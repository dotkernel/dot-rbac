<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Provider\RoleProviderInterface;
use Dot\Rbac\Role\Role;
use Dot\Rbac\Role\RoleService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoleServiceTest extends TestCase
{
    private RoleService $roleService;
    private RoleProviderInterface|MockObject $roleProvider;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $identityProvider   = $this->createMock(IdentityProviderInterface::class);
        $this->roleProvider = $this->createMock(RoleProviderInterface::class);

        $this->roleService = new RoleService($identityProvider, $this->roleProvider);
    }

    public function testMatchIdentityRoles(): void
    {
        $this->roleProvider->expects($this->any())->method('getRoles')
            ->willReturn(
                [new HierarchicalRole('test')]
            );

        $roles = ['roles'];
        $this->roleService->setGuestRole('testRole');

        $this->roleService->matchIdentityRoles($roles);

        $this->assertFalse($this->roleService->matchIdentityRoles($roles));
    }

    public function testRoleServiceAccessor(): void
    {
        $this->roleService->setGuestRole('tester');
        $result = $this->roleService->getGuestRole();
        $this->assertSame('tester', $result);
    }

    public function testMatchIdentityRolesWithRole(): void
    {
        $this->roleProvider->expects($this->any())->method('getRoles')
            ->willReturn(
                [new Role('test')]
            );

        $roles = ['roles'];
        $this->roleService->setGuestRole('testRole');

        $this->roleService->matchIdentityRoles($roles);

        $this->assertFalse($this->roleService->matchIdentityRoles($roles));
    }
}
