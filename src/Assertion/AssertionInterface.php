<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Assertion;

use Dot\Authorization\AuthorizationInterface;

/**
 * Interface AssertionInterface
 * @package N3vrax\DkRbac\Assertion
 */
interface AssertionInterface
{
    /**
     * @param AuthorizationInterface $authorization
     * @param mixed|null $context
     * @return bool
     */
    public function assert(AuthorizationInterface $authorization, $context = null): bool;
}
