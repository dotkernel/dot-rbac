<?php

declare(strict_types=1);

namespace Dot\Rbac\Plugin;

use Psr\Container\ContainerInterface;

/**
 * Creates a class that has no constructor arguments, or one array argument.
 */
final class InvokableFactory
{
    /**
     * @param array<array-key, mixed>|null $options
     */
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): object
    {
        return $options === null ? new $requestedName() : new $requestedName($options);
    }
}
