<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
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
