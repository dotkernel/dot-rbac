<?php

declare(strict_types=1);

namespace DotTest\Rbac\Identity;

use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Laminas\Authentication\AuthenticationServiceInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class AuthenticationIdentityProviderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetIdentity()
    {
        $identity             = $this->createMock(AuthenticationServiceInterface::class);
        $authIdentityProvider = new AuthenticationIdentityProvider($identity);

        $this->assertNull($authIdentityProvider->getIdentity());
    }
}
