<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/15/2016
 * Time: 1:32 AM
 */

namespace Dot\Rbac\Identity;

use N3vrax\DkAuthentication\AuthenticationInterface;
use N3vrax\DkAuthorization\Identity\IdentityInterface;

class AuthenticationIdentityProvider implements IdentityProviderInterface
{
    /**
     * @var AuthenticationInterface
     */
    protected $authentication;

    public function __construct(AuthenticationInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @return IdentityInterface
     * @throws \Exception
     */
    public function getIdentity()
    {
        $identity = $this->authentication->getIdentity();
        if(!is_null($identity) && !$identity instanceof IdentityInterface) {
            throw new \Exception(sprintf(
                'Authenticated identity must be an instance of %s, "%s" given',
                IdentityInterface::class,
                is_object($identity) ? get_class($identity) : gettype($identity)
            ));
        }
        return $identity;
    }
}