<?php
/**
 * @copyright: DotKernel
 * @library: dot-rbac
 * @author: n3vrax
 * Date: 2/1/2017
 * Time: 12:33 AM
 */

declare(strict_types = 1);

namespace Dot\Rbac\Assertion;

use Dot\Rbac\Exception\RuntimeException;
use Interop\Container\ContainerInterface;

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
        return $assertionManager->get($type, $specs['options'] ?? []);
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
