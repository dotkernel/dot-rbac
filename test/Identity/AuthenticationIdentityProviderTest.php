<?php

declare(strict_types=1);

namespace DotTest\Rbac\Identity;

use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use DotTest\Rbac\TestData\Identity;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\NonPersistent;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthenticationIdentityProvider::class)]
class AuthenticationIdentityProviderTest extends TestCase
{
    public function testGetIdentity(): void
    {
        $storage  = new NonPersistent();
        $provider = new AuthenticationIdentityProvider(new AuthenticationService($storage));
        $this->assertNull($provider->getIdentity());

        $identity = new Identity(['user']);
        $storage->write($identity);
        $this->assertSame($identity, $provider->getIdentity());
    }
}
