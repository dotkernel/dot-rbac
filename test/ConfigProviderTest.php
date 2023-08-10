<?php

declare(strict_types=1);

namespace DotTest\Rbac;

use Dot\Rbac\Assertion\AssertionPluginManager;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\ConfigProvider;
use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Dot\Rbac\Options\AuthorizationOptions;
use Dot\Rbac\Role\Provider\RoleProviderPluginManager;
use Dot\Rbac\Role\RoleService;
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
        $factories = $this->config['dependencies']['factories'];
        $this->assertArrayHasKey('factories', $this->config['dependencies']);
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
}
