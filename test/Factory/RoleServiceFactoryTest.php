<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Authorization\Role\RoleInterface;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Factory\RoleServiceFactory;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Plugin\AbstractPluginManager;
use Dot\Rbac\Plugin\InvokableFactory;
use Dot\Rbac\Role\Provider\Factory;
use Dot\Rbac\Role\Provider\InMemoryRoleProvider;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\Role;
use Dot\Rbac\Role\RoleService;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\IdentityProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function array_map;

#[CoversClass(RoleServiceFactory::class)]
#[UsesClass(AuthorizationOptions::class)]
#[UsesClass(RoleService::class)]
#[UsesClass(Factory::class)]
#[UsesClass(AbstractPluginManager::class)]
#[UsesClass(RoleProviderPluginManager::class)]
#[UsesClass(InvokableFactory::class)]
#[UsesClass(InMemoryRoleProvider::class)]
#[UsesClass(Role::class)]
class RoleServiceFactoryTest extends TestCase
{
    public function testWillCreateService(): void
    {
        $options = new AuthorizationOptions([
            'guest_role'    => 'visitor',
            'role_provider' => [
                'type'    => 'inmemoryroleprovider',
                'options' => ['roles' => ['visitor' => ['permissions' => ['view']]]],
            ],
        ]);

        $service = (new RoleServiceFactory())($this->createContainer($options));

        $this->assertSame('visitor', $service->getGuestRole());
        $roles = $service->getIdentityRoles();
        $this->assertSame(['visitor'], array_map(static fn (RoleInterface $role): string => $role->getName(), $roles));
        $this->assertInstanceOf(Role::class, $roles[0]);
        $this->assertTrue($roles[0]->hasPermission('view'));
    }

    public function testWillNotCreateServiceWithoutRoleProvider(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No role provider was set for authorization');
        (new RoleServiceFactory())($this->createContainer(new AuthorizationOptions([])));
    }

    private function createContainer(AuthorizationOptions $options): Container
    {
        $empty = new Container();

        return new Container([
            AuthorizationOptions::class      => $options,
            IdentityProviderInterface::class => new IdentityProvider(),
            RoleProviderPluginManager::class => new RoleProviderPluginManager($empty),
        ]);
    }
}
