<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

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
    public function assert(AuthorizationInterface $authorization, $context = null);
}
