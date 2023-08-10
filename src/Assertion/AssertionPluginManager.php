<?php

declare(strict_types=1);

namespace Dot\Rbac\Assertion;

use Laminas\ServiceManager\AbstractPluginManager;

/**
 * @template-extends AbstractPluginManager<AbstractPluginManager>
 */
class AssertionPluginManager extends AbstractPluginManager
{
    /** @var string  */
    protected $instanceOf = AssertionInterface::class;
}
