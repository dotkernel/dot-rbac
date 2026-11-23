<?php

declare(strict_types=1);

namespace Dot\Rbac\Plugin;

use Dot\Rbac\Exception\InvalidArgumentException;
use Dot\Rbac\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

use function class_exists;
use function get_debug_type;
use function is_callable;
use function is_string;
use function sprintf;

/**
 * Builds plugins from a map of factories and aliases. It works with any PSR-11 container.
 *
 * A factory gets the container, the resolved plugin name and the options, in this order.
 *
 * @template T of object
 */
abstract class AbstractPluginManager
{
    /** @var array<string, string> */
    protected array $aliases = [];

    /** @var array<string, string|callable> */
    protected array $factories = [];

    /**
     * @param array{aliases?: array<string, string>, factories?: array<string, string|callable>} $config
     */
    public function __construct(protected ContainerInterface $container, array $config = [])
    {
        $this->aliases   = ($config['aliases'] ?? []) + $this->aliases;
        $this->factories = ($config['factories'] ?? []) + $this->factories;
    }

    /**
     * @return class-string<T>
     */
    abstract protected function getInstanceOf(): string;

    public function has(string $name): bool
    {
        return isset($this->factories[$this->aliases[$name] ?? $name]);
    }

    /**
     * @param array<array-key, mixed>|null $options
     * @return T
     */
    public function build(string $name, ?array $options = null): object
    {
        $resolved = $this->aliases[$name] ?? $name;
        if (! isset($this->factories[$resolved])) {
            throw new InvalidArgumentException(sprintf(
                'No factory is registered for plugin "%s" in %s',
                $name,
                static::class
            ));
        }

        $factory = $this->factories[$resolved];
        if (is_string($factory) && class_exists($factory)) {
            $factory = new $factory();
        }

        if (! is_callable($factory)) {
            throw new RuntimeException(sprintf('The factory for plugin "%s" is not callable', $name));
        }

        $instance = $factory($this->container, $resolved, $options);
        $this->validate($instance);

        return $instance;
    }

    /**
     * @phpstan-assert T $instance
     */
    public function validate(mixed $instance): void
    {
        $instanceOf = $this->getInstanceOf();
        if (! $instance instanceof $instanceOf) {
            throw new InvalidArgumentException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                static::class,
                $instanceOf,
                get_debug_type($instance)
            ));
        }
    }
}
