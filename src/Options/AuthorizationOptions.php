<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

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
    protected $assertionMap = [];

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
    public function getGuestRole()
    {
        return $this->guestRole;
    }

    /**
     * @param string $guestRole
     */
    public function setGuestRole($guestRole)
    {
        $this->guestRole = $guestRole;
    }

    /**
     * @return array
     */
    public function getAssertionMap()
    {
        return $this->assertionMap;
    }

    /**
     * @param array $assertionMap
     */
    public function setAssertionMap($assertionMap)
    {
        $this->assertionMap = $assertionMap;
    }

    /**
     * @return array
     */
    public function getRoleProvider()
    {
        return $this->roleProvider;
    }

    /**
     * @param array $roleProvider
     */
    public function setRoleProvider($roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }


}