<?php

declare(strict_types=1);

namespace DotTest\Rbac\Options;

use Dot\Rbac\Options\AuthorizationOptions;
use PHPUnit\Framework\TestCase;

class AuthorizationOptionsTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $test = ['test' => 'testOption'];

        $authOptions = new AuthorizationOptions($test);

        $authOptions->setGuestRole('testRole');
        $this->assertSame('testRole', $authOptions->getGuestRole());

        $authOptions->setRoleProvider(['test' => 'testRole']);
        $this->assertSame(['test' => 'testRole'], $authOptions->getRoleProvider());

        $authOptions->setAssertions(['test' => 'assertionTest']);
        $this->assertSame(['test' => 'assertionTest'], $authOptions->getAssertions());
    }
}
