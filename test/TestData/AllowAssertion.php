<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionInterface;

final class AllowAssertion implements AssertionInterface
{
    public int $calls = 0;

    public function assert(AuthorizationInterface $authorization, mixed $context = null): bool
    {
        $this->calls++;

        return true;
    }
}
