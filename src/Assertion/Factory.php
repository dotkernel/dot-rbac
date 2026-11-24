<?php

declare(strict_types=1);

namespace Dot\Rbac\Assertion;

use Dot\Rbac\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

use function get_debug_type;
use function is_array;
use function is_string;
use function sprintf;

class Factory
{
    public function __construct(
        protected ContainerInterface $container,
        protected ?AssertionPluginManager $assertionPluginManager = null
    ) {
    }

    /**
     * @param array<string, mixed> $specs
     */
    public function create(array $specs): AssertionInterface
    {
        $type = $specs['type'] ?? null;
        if (! is_string($type) || $type === '') {
            throw new RuntimeException(sprintf('Invalid assertion type of type %s', get_debug_type($type)));
        }

        $options = $specs['options'] ?? null;
        if ($options !== null && ! is_array($options)) {
            throw new RuntimeException('Assertion options must be an array');
        }

        return $this->getAssertionPluginManager()->build($type, $options);
    }

    public function getAssertionPluginManager(): AssertionPluginManager
    {
        if ($this->assertionPluginManager === null) {
            $this->assertionPluginManager = new AssertionPluginManager($this->container, []);
        }

        return $this->assertionPluginManager;
    }
}
