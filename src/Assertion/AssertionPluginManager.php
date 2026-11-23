<?php

declare(strict_types=1);

namespace Dot\Rbac\Assertion;

use Dot\Rbac\Plugin\AbstractPluginManager;

/**
 * @extends AbstractPluginManager<AssertionInterface>
 */
class AssertionPluginManager extends AbstractPluginManager
{
    protected function getInstanceOf(): string
    {
        return AssertionInterface::class;
    }
}
