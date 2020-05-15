<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Identity;

use Dot\Authorization\Identity\IdentityInterface;

/**
 * Interface IdentityProviderInterface
 * @package Dot\Rbac\Identity
 */
interface IdentityProviderInterface
{
    /**
     * @return mixed|null
     */
    public function getIdentity();
}
