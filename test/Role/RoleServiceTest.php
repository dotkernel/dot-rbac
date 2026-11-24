<?php

declare(strict_types=1);

namespace DotTest\Rbac\Role;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Exception\InvalidArgumentException;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Provider\InMemoryRoleProvider;
use Dot\Rbac\Role\Role;
use Dot\Rbac\Role\RoleFlattener;
use Dot\Rbac\Role\RoleService;
use DotTest\Rbac\TestData\Identity;
use DotTest\Rbac\TestData\IdentityProvider;
use DotTest\Rbac\TestData\NamedObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use stdClass;

use function array_map;
use function array_values;

#[CoversClass(RoleService::class)]
#[UsesClass(InMemoryRoleProvider::class)]
#[UsesClass(Role::class)]
#[UsesClass(HierarchicalRole::class)]
#[UsesClass(RoleFlattener::class)]
class RoleServiceTest extends TestCase
{
    private InMemoryRoleProvider $roleProvider;

    public function setUp(): void
    {
        $this->roleProvider = new InMemoryRoleProvider([
            'roles' => [
                'admin' => ['children' => ['user'], 'permissions' => ['delete']],
                'user'  => ['permissions' => ['edit']],
                'guest' => ['permissions' => ['view']],
            ],
        ]);
    }

    public function testGuestRoleAccessors(): void
    {
        $roleService = $this->createRoleService();
        $this->assertSame('guest', $roleService->getGuestRole());

        $roleService->setGuestRole('tester');
        $this->assertSame('tester', $roleService->getGuestRole());
    }

    public function testGuestRolesWithoutIdentity(): void
    {
        $roleService = $this->createRoleService();

        $this->assertNull($roleService->getIdentity());
        $this->assertSame(['guest'], $this->names($roleService->getIdentityRoles()));
    }

    public function testIdentityRolesAreResolved(): void
    {
        $user        = new Role('custom');
        $roleService = $this->createRoleService(new Identity(['admin', $user]));

        $this->assertSame(['custom', 'admin'], $this->names($roleService->getIdentityRoles()));
    }

    public function testIdentityWithGetRolesWithoutInterface(): void
    {
        $identity = new class {
            /**
             * @return list<string>
             */
            public function getRoles(): array
            {
                return ['user'];
            }
        };

        $this->assertSame(['user'], $this->names($this->createRoleService($identity)->getIdentityRoles()));
    }

    public function testIdentityWithoutRolesThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The identity must implement');
        $this->createRoleService(new stdClass())->getIdentityRoles();
    }

    public function testFalsyIdentityIsNotTreatedAsGuest(): void
    {
        $this->expectException(RuntimeException::class);
        $this->createRoleService('0')->getIdentityRoles();
    }

    public function testMatchIdentityRolesIncludesChildren(): void
    {
        $roleService = $this->createRoleService(new Identity(['admin']));

        $this->assertTrue($roleService->matchIdentityRoles(['user']));
        $this->assertTrue($roleService->matchIdentityRoles([new Role('admin')]));
        $this->assertFalse($roleService->matchIdentityRoles(['guest']));
    }

    public function testMatchIdentityRolesForGuest(): void
    {
        $roleService = $this->createRoleService();

        $this->assertTrue($roleService->matchIdentityRoles(['guest']));
        $this->assertFalse($roleService->matchIdentityRoles(['user']));
    }

    public function testMatchIdentityRolesWithoutIdentityRoles(): void
    {
        $this->assertFalse($this->createRoleService(new Identity([]))->matchIdentityRoles(['user']));
    }

    public function testResolveRolesKeepsRoleObjects(): void
    {
        $role = new Role('custom');

        $this->assertSame([$role], $this->createRoleService()->resolveRoles([$role]));
    }

    public function testIntegerRoleIsConverted(): void
    {
        $this->assertSame(['1'], $this->names($this->createRoleService()->resolveRoles([1])));
    }

    public function testRoleNamedLikeClass(): void
    {
        $roles = $this->createRoleService()->resolveRoles(['ReflectionClass']);

        $this->assertSame(['ReflectionClass'], $this->names($roles));
    }

    public function testObjectWithGetNameIsConverted(): void
    {
        $roles = $this->createRoleService()->resolveRoles([new NamedObject('user')]);

        $this->assertSame(['user'], $this->names($roles));
        $this->assertInstanceOf(Role::class, $roles[0]);
        $this->assertTrue($roles[0]->hasPermission('edit'));
    }

    public function testInvalidRoleThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid role of type array');
        $this->createRoleService()->resolveRoles([['admin']]);
    }

    public function testSetters(): void
    {
        $roleService = $this->createRoleService();
        $roleService->setIdentityProvider(new IdentityProvider(new Identity(['user'])));
        $roleService->setRoleProvider(new InMemoryRoleProvider());

        $roles = $roleService->getIdentityRoles();
        $this->assertSame(['user'], $this->names($roles));
        $this->assertInstanceOf(Role::class, $roles[0]);
        $this->assertSame([], $roles[0]->getPermissions());
    }

    private function createRoleService(mixed $identity = null): RoleService
    {
        return new RoleService(new IdentityProvider($identity), $this->roleProvider);
    }

    /**
     * @param array<RoleInterface> $roles
     * @return list<string>
     */
    private function names(array $roles): array
    {
        return array_values(array_map(static fn (RoleInterface $role): string => $role->getName(), $roles));
    }
}
