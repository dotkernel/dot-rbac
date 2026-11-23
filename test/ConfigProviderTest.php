<?php

declare(strict_types=1);

namespace DotTest\Rbac;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\ConfigProvider;
use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Dot\Rbac\Identity\IdentityProviderInterface;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Rbac;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleService;
use Dot\Rbac\Role\RoleServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigProvider::class)]
class ConfigProviderTest extends TestCase
{
    /** @var array<string, mixed> */
    protected array $config;

    protected function setUp(): void
    {
        $this->config = (new ConfigProvider())();
    }

    public function testHasDependencies(): void
    {
        $this->assertArrayHasKey('dependencies', $this->config);
        $this->assertSame((new ConfigProvider())->getDependencyConfig(), $this->config['dependencies']);
    }

    public function testDependenciesHasFactories(): void
    {
        $factories = (new ConfigProvider())->getDependencyConfig()['factories'];

        $this->assertArrayHasKey(Rbac::class, $factories);
        $this->assertArrayHasKey(AuthenticationIdentityProvider::class, $factories);
        $this->assertArrayHasKey(RoleProviderPluginManager::class, $factories);
        $this->assertArrayHasKey(RoleService::class, $factories);
        $this->assertArrayHasKey(AuthorizationOptions::class, $factories);
        $this->assertArrayHasKey(AssertionPluginManager::class, $factories);
        $this->assertArrayHasKey(AuthorizationService::class, $factories);
    }

    public function testDependenciesHasAliases(): void
    {
        $aliases = (new ConfigProvider())->getDependencyConfig()['aliases'];

        $this->assertSame(Rbac::class, $aliases[RbacInterface::class]);
        $this->assertSame(AuthorizationService::class, $aliases[AuthorizationInterface::class]);
        $this->assertSame(RoleService::class, $aliases[RoleServiceInterface::class]);
        $this->assertSame(AuthenticationIdentityProvider::class, $aliases[IdentityProviderInterface::class]);
    }

    public function testDotAuthorizationHasConfig(): void
    {
        $this->assertSame(
            [
                'guest_role'            => 'guest',
                'assertions'            => [],
                'assertion_manager'     => [],
                'role_provider'         => [],
                'role_provider_manager' => [],
            ],
            $this->config['dot_authorization']
        );
    }
}
