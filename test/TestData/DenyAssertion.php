<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionInterface;

final class DenyAssertion implements AssertionInterface
{
    public function assert(AuthorizationInterface $authorization, mixed $context = null): bool
    {
        return false;
    }
}
