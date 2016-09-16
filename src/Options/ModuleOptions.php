<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/21/2016
 * Time: 2:05 PM
 */

namespace Dot\Rbac\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
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