<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

use Dot\Authorization\AuthorizationInterface;
use Dot\Rbac\Assertion\AssertionInterface;

/**
 * Allows access when the context is the same as the "allowed" option. The default option is "own".
 */
final class ContextAssertion implements AssertionInterface
{
    /**
     * @param array<string, mixed>|null $options
     */
    public function __construct(private readonly ?array $options = null)
    {
    }

    public function assert(AuthorizationInterface $authorization, mixed $context = null): bool
    {
        $allowed = $this->options['allowed'] ?? 'own';

        return $context === $allowed;
    }
}
