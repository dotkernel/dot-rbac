<?php

declare(strict_types=1);

namespace Dot\Rbac\Assertion;

use Dot\Rbac\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

use function sprintf;

class Factory
{
    protected ContainerInterface $container;
    protected ?AssertionPluginManager $assertionPluginManager;

    public function __construct(ContainerInterface $container, ?AssertionPluginManager $assertionPluginManager = null)
    {
        $this->container              = $container;
        $this->assertionPluginManager = $assertionPluginManager;
    }

    public function create(array $specs): AssertionInterface
    {
        $type = $specs['type'] ?? '';
        if (empty($type)) {
            throw new RuntimeException(sprintf('Invalid assertion type `%s`', $type));
        }

        $assertionManager = $this->getAssertionPluginManager();
        return $assertionManager->get($type, $specs['options'] ?? null);
    }

    public function getAssertionPluginManager(): AssertionPluginManager
    {
        if (! $this->assertionPluginManager) {
            $this->assertionPluginManager = new AssertionPluginManager($this->container, []);
        }

        return $this->assertionPluginManager;
    }
}
