<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Factory\RbacFactory;
use Dot\Rbac\Rbac;
use DotTest\Rbac\TestData\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RbacFactory::class)]
#[UsesClass(Rbac::class)]
class RbacFactoryTest extends TestCase
{
    public function testCreatesRbac(): void
    {
        $this->assertFalse((new RbacFactory())(new Container())->isGranted('view', []));
    }
}
