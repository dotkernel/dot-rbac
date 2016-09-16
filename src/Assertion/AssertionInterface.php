<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/14/2016
 * Time: 9:59 PM
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