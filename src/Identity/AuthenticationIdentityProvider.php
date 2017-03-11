<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Identity;

use Dot\Authentication\AuthenticationInterface;
use Dot\Authorization\Identity\IdentityInterface;
use Dot\Rbac\Exception\RuntimeException;

/**
 * Class AuthenticationIdentityProvider
 * @package Dot\Rbac\Identity
 */
class AuthenticationIdentityProvider implements IdentityProviderInterface
{
    /**
     * @var AuthenticationInterface
     */
    protected $authentication;

    /**
     * AuthenticationIdentityProvider constructor.
     * @param AuthenticationInterface $authentication
     */
    public function __construct(AuthenticationInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @return IdentityInterface
     * @throws \Exception
     */
    public function getIdentity(): ?IdentityInterface
    {
        $identity = $this->authentication->getIdentity();

        if (!is_null($identity) && !$identity instanceof IdentityInterface) {
            throw new RuntimeException(sprintf(
                'Authenticated identity must be an instance of %s, "%s" given',
                IdentityInterface::class,
                is_object($identity) ? get_class($identity) : gettype($identity)
            ));
        }
        return $identity;
    }
}
