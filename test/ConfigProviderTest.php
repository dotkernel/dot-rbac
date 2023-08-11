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
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleService;
use Dot\Rbac\Role\RoleServiceInterface;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    protected array $config;

    protected function setup(): void
    {
        $this->config = (new ConfigProvider())();
    }

    public function testHasDependencies(): void
    {
        $this->assertArrayHasKey('dependencies', $this->config);
    }

    public function testHasDotAuthorization(): void
    {
        $this->assertArrayHasKey('dot_authorization', $this->config);
    }

    public function testDependenciesHasFactories(): void
    {
        $this->assertArrayHasKey('factories', $this->config['dependencies']);
        $factories = $this->config['dependencies']['factories'];
        $this->assertArrayHasKey(AuthenticationIdentityProvider::class, $factories);
        $this->assertArrayHasKey(RoleProviderPluginManager::class, $factories);
        $this->assertArrayHasKey(RoleService::class, $factories);
        $this->assertArrayHasKey(AuthorizationOptions::class, $factories);
        $this->assertArrayHasKey(AssertionPluginManager::class, $factories);
        $this->assertArrayHasKey(AuthorizationService::class, $factories);
    }

    public function testDotAuthorizationHasConfig(): void
    {
        $config = $this->config['dot_authorization'];
        $this->assertArrayHasKey('guest_role', $config);
        $this->assertArrayHasKey('assertions', $config);
        $this->assertArrayHasKey('assertion_manager', $config);
        $this->assertArrayHasKey('role_provider', $config);
        $this->assertArrayHasKey('role_provider_manager', $config);
    }

    public function testDependenciesHasAliases(): void
    {
        $this->assertArrayHasKey('aliases', $this->config['dependencies']);
        $aliases = $this->config['dependencies']['aliases'];
        $this->assertArrayHasKey(RbacInterface::class, $aliases);
        $this->assertArrayHasKey(AuthorizationInterface::class, $aliases);
        $this->assertArrayHasKey(RoleServiceInterface::class, $aliases);
        $this->assertArrayHasKey(IdentityProviderInterface::class, $aliases);
    }
}
