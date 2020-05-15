<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Identity;

use Dot\Authorization\Identity\IdentityInterface;
use Dot\Rbac\Exception\RuntimeException;
use Laminas\Authentication\AuthenticationServiceInterface;

/**
 * Class AuthenticationIdentityProvider
 * @package Dot\Rbac\Identity
 */
class AuthenticationIdentityProvider implements IdentityProviderInterface
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authentication;

    /**
     * AuthenticationIdentityProvider constructor.
     * @param AuthenticationServiceInterface $authentication
     */
    public function __construct(AuthenticationServiceInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @return mixed|null
     * @throws \Exception
     */
    public function getIdentity()
    {
        $identity = $this->authentication->getIdentity();

        return $identity;
    }
}
