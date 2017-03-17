<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Assertion;

use Dot\Rbac\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

/**
 * Class Factory
 * @package Dot\Rbac\Assertion
 */
class Factory
{
    /** @var  ContainerInterface */
    protected $container;

    /** @var  AssertionPluginManager */
    protected $assertionPluginManager;

    /**
     * Factory constructor.
     * @param ContainerInterface $container
     * @param AssertionPluginManager|null $assertionPluginManager
     */
    public function __construct(ContainerInterface $container, AssertionPluginManager $assertionPluginManager = null)
    {
        $this->container = $container;
        $this->assertionPluginManager = $assertionPluginManager;
    }

    /**
     * @param array $specs
     * @return AssertionInterface
     */
    public function create(array $specs): AssertionInterface
    {
        $type = $specs['type'] ?? '';
        if (empty($type)) {
            throw new RuntimeException(sprintf('Invalid assertion type `%s`', $type));
        }

        $assertionManager = $this->getAssertionPluginManager();
        return $assertionManager->get($type, $specs['options'] ?? null);
    }

    /**
     * @return AssertionPluginManager
     */
    public function getAssertionPluginManager(): AssertionPluginManager
    {
        if (!$this->assertionPluginManager) {
            $this->assertionPluginManager = new AssertionPluginManager($this->container, []);
        }

        return $this->assertionPluginManager;
    }
}
