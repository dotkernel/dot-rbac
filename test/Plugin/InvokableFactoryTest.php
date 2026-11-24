<?php

declare(strict_types=1);

namespace DotTest\Rbac\Plugin;

use Dot\Rbac\Plugin\InvokableFactory;
use DotTest\Rbac\TestData\AllowAssertion;
use DotTest\Rbac\TestData\Container;
use DotTest\Rbac\TestData\ContextAssertion;
use DotTest\Rbac\TestData\IdentityProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvokableFactory::class)]
class InvokableFactoryTest extends TestCase
{
    public function testCreatesWithoutOptions(): void
    {
        $instance = (new InvokableFactory())(new Container(), AllowAssertion::class);

        $this->assertInstanceOf(AllowAssertion::class, $instance);
    }

    public function testCreatesWithOptions(): void
    {
        $instance = (new InvokableFactory())(new Container(), ContextAssertion::class, ['allowed' => 'mine']);
        $this->assertInstanceOf(ContextAssertion::class, $instance);

        $authorization = (new InvokableFactory())(new Container(), IdentityProvider::class, []);
        $this->assertInstanceOf(IdentityProvider::class, $authorization);
    }
}
