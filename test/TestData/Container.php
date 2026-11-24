<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

use Psr\Container\ContainerInterface;

use function array_key_exists;
use function in_array;

/**
 * Minimal PSR-11 container: returns the services it was constructed with.
 */
final class Container implements ContainerInterface
{
    /**
     * @param array<string, mixed> $services
     * @param list<string> $failing Identifiers for which get() throws a ContainerException.
     */
    public function __construct(private readonly array $services = [], private readonly array $failing = [])
    {
    }

    public function get(string $id): mixed
    {
        if (in_array($id, $this->failing, true)) {
            throw new ContainerException($id);
        }

        if (! array_key_exists($id, $this->services)) {
            throw new ServiceNotFoundException($id);
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
