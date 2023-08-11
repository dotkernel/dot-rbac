<?php

declare(strict_types=1);

namespace Dot\Rbac\Assertion;

use Dot\Authorization\AuthorizationInterface;

interface AssertionInterface
{
    public function assert(AuthorizationInterface $authorization, mixed $context = null): bool;
}
