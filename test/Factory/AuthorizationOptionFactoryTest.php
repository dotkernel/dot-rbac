<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Factory\AuthorizationOptionsFactory;
use Dot\Rbac\Options\AuthorizationOptions;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\ContainerException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthorizationOptionsFactory::class)]
#[UsesClass(AuthorizationOptions::class)]
class AuthorizationOptionFactoryTest extends TestCase
{
    public function testCanCreateOptions(): void
    {
        $container = new Container(['config' => ['dot_authorization' => ['guest_role' => 'visitor']]]);

        $this->assertSame('visitor', (new AuthorizationOptionsFactory())($container)->getGuestRole());
    }

    public function testCanCreateOptionsWithoutConfig(): void
    {
        $options = (new AuthorizationOptionsFactory())(new Container(['config' => []]));

        $this->assertSame('guest', $options->getGuestRole());
    }

    public function testContainerExceptionIsNotCaught(): void
    {
        $this->expectException(ContainerException::class);
        (new AuthorizationOptionsFactory())(new Container([], ['config']));
    }
}
