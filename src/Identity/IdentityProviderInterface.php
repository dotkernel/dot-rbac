<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 5/13/2016
 * Time: 9:22 PM
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