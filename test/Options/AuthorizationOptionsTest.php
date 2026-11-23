<?php

declare(strict_types=1);

namespace DotTest\Rbac\Options;

use Dot\Rbac\Options\AuthorizationOptions;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthorizationOptions::class)]
class AuthorizationOptionsTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $authOptions = new AuthorizationOptions(['unknown' => 'is ignored']);
        $this->assertSame('guest', $authOptions->getGuestRole());
        $this->assertSame([], $authOptions->getAssertions());
        $this->assertSame([], $authOptions->getRoleProvider());

        $authOptions->setGuestRole('testRole');
        $this->assertSame('testRole', $authOptions->getGuestRole());

        $authOptions->setRoleProvider(['type' => 'InMemory']);
        $this->assertSame(['type' => 'InMemory'], $authOptions->getRoleProvider());

        $authOptions->setAssertions([['type' => 'Assertion', 'permissions' => ['edit']]]);
        $this->assertSame([['type' => 'Assertion', 'permissions' => ['edit']]], $authOptions->getAssertions());
    }

    public function testOptionsFromConfiguration(): void
    {
        $authOptions = new AuthorizationOptions([
            'guest_role'            => 'visitor',
            'role_provider'         => ['type' => 'InMemory'],
            'assertions'            => [['type' => 'Assertion']],
            'assertion_manager'     => [],
            'role_provider_manager' => [],
        ]);

        $this->assertSame('visitor', $authOptions->getGuestRole());
        $this->assertSame(['type' => 'InMemory'], $authOptions->getRoleProvider());
        $this->assertSame([['type' => 'Assertion']], $authOptions->getAssertions());
    }

    public function testNullOptions(): void
    {
        $this->assertSame('guest', (new AuthorizationOptions(null))->getGuestRole());
    }
}
