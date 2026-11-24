<?php

declare(strict_types=1);

namespace DotTest\Rbac\Authorization;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Exception\InvalidArgumentException;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use Dot\Rbac\Rbac;
use Dot\Rbac\Role\HierarchicalRole;
use Dot\Rbac\Role\Provider\InMemoryRoleProvider;
use Dot\Rbac\Role\Role;
use Dot\Rbac\Role\RoleFlattener;
use Dot\Rbac\Role\RoleService;
use DotTest\Rbac\TestData\AllowAssertion;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\ContextAssertion;
use DotTest\Rbac\TestData\DenyAssertion;
use DotTest\Rbac\TestData\Identity;
use DotTest\Rbac\TestData\IdentityProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use TypeError;

#[CoversClass(AuthorizationService::class)]
#[UsesClass(Rbac::class)]
#[UsesClass(RoleService::class)]
#[UsesClass(RoleFlattener::class)]
#[UsesClass(InMemoryRoleProvider::class)]
#[UsesClass(Role::class)]
#[UsesClass(HierarchicalRole::class)]
#[UsesClass(Factory::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(AssertionPluginManager::class)]
#[UsesClass(InvokableFactory::class)]
class AuthorizationServiceTest extends TestCase
{
    public function testGuestPermissions(): void
    {
        $service = $this->createService();

        $this->assertNull($service->getIdentity());
        $this->assertTrue($service->isGranted('view'));
        $this->assertFalse($service->isGranted('edit'));
    }

    public function testIdentityPermissionsIncludeChildren(): void
    {
        $service = $this->createService(new Identity(['admin']));

        $this->assertTrue($service->isGranted('delete'));
        $this->assertTrue($service->isGranted('edit'));
        $this->assertTrue($service->isGranted('view'));
        $this->assertFalse($service->isGranted('unknown'));
    }

    public function testIsGrantedWithRoleNames(): void
    {
        $service = $this->createService();

        $this->assertTrue($service->isGranted('edit', ['user']));
        $this->assertFalse($service->isGranted('delete', ['user']));
        $this->assertTrue($service->isGranted('view', ['guest']));
    }

    public function testIsGrantedWithRoleObjects(): void
    {
        $role = new Role('custom');
        $role->addPermission('custom-permission');

        $this->assertTrue($this->createService()->isGranted('custom-permission', [$role]));
    }

    public function testIsGrantedWithoutIdentityRoles(): void
    {
        $this->assertFalse($this->createService(new Identity([]))->isGranted('view'));
    }

    public function testAddAssertion(): void
    {
        $service = $this->createService();
        $this->assertFalse($service->hasAssertion('edit'));

        $service->addAssertion('edit', new AllowAssertion());
        $this->assertTrue($service->hasAssertion('edit'));
        $this->assertFalse($service->hasAssertion('view'));
    }

    public function testAddEmptyAssertionThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('An assertion specification must not be empty');
        $this->createService()->addAssertion('edit', []);
    }

    public function testAddAssertionWithWrongTypeThrowsTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->createService()->addAssertion('edit', $this->invalidAssertion());
    }

    public function testAssertionAllowsAccess(): void
    {
        $assertion = new AllowAssertion();
        $service   = $this->createService();
        $service->addAssertion('edit', $assertion);

        $this->assertTrue($service->isGranted('edit', ['user']));
        $this->assertSame(1, $assertion->calls);
    }

    public function testAssertionIsNotCalledWithoutPermission(): void
    {
        $assertion = new AllowAssertion();
        $service   = $this->createService();
        $service->addAssertion('edit', $assertion);

        $this->assertFalse($service->isGranted('edit', ['guest']));
        $this->assertSame(0, $assertion->calls);
    }

    public function testAssertionDeniesAccess(): void
    {
        $after   = new AllowAssertion();
        $service = $this->createService();
        $service->addAssertion('edit', new DenyAssertion());
        $service->addAssertion('edit', $after);

        $this->assertFalse($service->isGranted('edit', ['user']));
        $this->assertSame(0, $after->calls);
    }

    public function testAssertionFromSpecification(): void
    {
        $service = $this->createService(new Identity(['user']));
        $service->addAssertion('edit', ['type' => ContextAssertion::class, 'options' => ['allowed' => 'mine']]);

        $this->assertTrue($service->isGranted('edit', [], 'mine'));
        $this->assertFalse($service->isGranted('edit', [], 'other'));
    }

    private function invalidAssertion(): mixed
    {
        return 'testAssertion';
    }

    private function createService(mixed $identity = null): AuthorizationService
    {
        $roleProvider = new InMemoryRoleProvider([
            'roles' => [
                'admin' => ['children' => ['user'], 'permissions' => ['delete']],
                'user'  => ['children' => ['guest'], 'permissions' => ['edit']],
                'guest' => ['permissions' => ['view']],
            ],
        ]);
        $manager      = new AssertionPluginManager(new Container(), [
            'factories' => [ContextAssertion::class => InvokableFactory::class],
        ]);

        return new AuthorizationService(
            new Rbac(),
            new RoleService(new IdentityProvider($identity), $roleProvider),
            new Factory(new Container(), $manager)
        );
    }
}
