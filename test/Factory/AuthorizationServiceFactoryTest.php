<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Factory\AuthorizationServiceFactory;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use Dot\Rbac\Rbac;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\Provider\InMemoryRoleProvider;
use Dot\Rbac\Role\Role;
use Dot\Rbac\Role\RoleFlattener;
use Dot\Rbac\Role\RoleService;
use Dot\Rbac\Role\RoleServiceInterface;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\ContextAssertion;
use DotTest\Rbac\TestData\IdentityProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthorizationServiceFactory::class)]
#[UsesClass(AuthorizationService::class)]
#[UsesClass(AuthorizationOptions::class)]
#[UsesClass(Factory::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(AssertionPluginManager::class)]
#[UsesClass(InvokableFactory::class)]
#[UsesClass(Rbac::class)]
#[UsesClass(RoleService::class)]
#[UsesClass(RoleFlattener::class)]
#[UsesClass(InMemoryRoleProvider::class)]
#[UsesClass(Role::class)]
class AuthorizationServiceFactoryTest extends TestCase
{
    public function testAddsAssertionsFromOptions(): void
    {
        $service = (new AuthorizationServiceFactory())($this->createContainer([
            ['type' => ContextAssertion::class, 'permissions' => ['edit', 'delete']],
            ['type' => ContextAssertion::class],
            ['type' => ContextAssertion::class, 'permissions' => 'view'],
            ['type' => ContextAssertion::class, 'permissions' => [1]],
            'not an array',
        ]));

        $this->assertTrue($service->hasAssertion('edit'));
        $this->assertTrue($service->hasAssertion('delete'));
        $this->assertFalse($service->hasAssertion('view'));
        $this->assertFalse($service->hasAssertion('1'));

        $this->assertTrue($service->isGranted('edit', ['user'], 'own'));
        $this->assertFalse($service->isGranted('edit', ['user'], 'other'));
        $this->assertTrue($service->isGranted('view', ['user'], 'other'));
    }

    public function testInvalidOptionsServiceThrowsException(): void
    {
        $container = new Container([
            RbacInterface::class          => new Rbac(),
            RoleServiceInterface::class   => $this->createRoleService(),
            AssertionPluginManager::class => new AssertionPluginManager(new Container()),
            AuthorizationOptions::class   => [],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('has an invalid type');
        (new AuthorizationServiceFactory())($container);
    }

    /**
     * @param array<array-key, mixed> $assertions
     */
    private function createContainer(array $assertions): Container
    {
        $empty = new Container();

        return new Container([
            RbacInterface::class          => new Rbac(),
            RoleServiceInterface::class   => $this->createRoleService(),
            AssertionPluginManager::class => new AssertionPluginManager($empty, [
                'factories' => [ContextAssertion::class => InvokableFactory::class],
            ]),
            AuthorizationOptions::class   => new AuthorizationOptions(['assertions' => $assertions]),
        ]);
    }

    private function createRoleService(): RoleService
    {
        return new RoleService(
            new IdentityProvider(),
            new InMemoryRoleProvider(['roles' => ['user' => ['permissions' => ['edit', 'view']]]])
        );
    }
}
