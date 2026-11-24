<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Factory\AuthenticationIdentityProviderFactory;
use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\Identity;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\NonPersistent;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthenticationIdentityProviderFactory::class)]
#[UsesClass(AuthenticationIdentityProvider::class)]
class AuthenticationIdentityProviderFactoryTest extends TestCase
{
    public function testWillCreateService(): void
    {
        $identity = new Identity(['user']);
        $storage  = new NonPersistent();
        $storage->write($identity);
        $container = new Container([AuthenticationService::class => new AuthenticationService($storage)]);

        $provider = (new AuthenticationIdentityProviderFactory())($container);
        $this->assertSame($identity, $provider->getIdentity());
    }
}
