<?php

declare(strict_types=1);

namespace Dot\Rbac\Assertion;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;

use function gettype;
use function is_object;
use function sprintf;

/**
 * @template-extends AbstractPluginManager<AbstractPluginManager>
 */
class AssertionPluginManager extends AbstractPluginManager
{
    protected string $instanceOf = AssertionInterface::class;

    public function validate(mixed $instance): void
    {
        if (! $instance instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                static::class,
                $this->instanceOf,
                is_object($instance) ? $instance::class : gettype($instance)
            ));
        }
    }
}
