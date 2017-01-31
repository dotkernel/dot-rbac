<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

declare(strict_types = 1);

namespace Dot\Rbac\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AuthorizationOptions
 * @package Dot\Rbac\Options
 */
class AuthorizationOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $guestRole = 'guest';

    /**
     * @var array
     */
    protected $assertions = [];

    /**
     * @var array
     */
    protected $roleProvider = [];

    /**
     * AuthorizationOptions constructor.
     * @param array|null|\Traversable $options
     */
    public function __construct($options)
    {
        $this->__strictMode__ = false;
        parent::__construct($options);
    }

    /**
     * @return string
     */
    public function getGuestRole(): string
    {
        return $this->guestRole;
    }

    /**
     * @param string $guestRole
     */
    public function setGuestRole(string $guestRole)
    {
        $this->guestRole = $guestRole;
    }

    /**
     * @return array
     */
    public function getAssertions(): array
    {
        return $this->assertions;
    }

    /**
     * @param array $assertions
     */
    public function setAssertions(array $assertions)
    {
        $this->assertions = $assertions;
    }

    /**
     * @return array
     */
    public function getRoleProvider(): array
    {
        return $this->roleProvider;
    }

    /**
     * @param array $roleProvider
     */
    public function setRoleProvider(array $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }
}
