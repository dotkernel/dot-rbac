<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac\Identity;

use Dot\Authorization\Identity\IdentityInterface;

/**
 * Interface IdentityProviderInterface
 * @package Dot\Rbac\Identity
 */
interface IdentityProviderInterface
{
    /**
     * @return IdentityInterface|null
     */
    public function getIdentity();
}
