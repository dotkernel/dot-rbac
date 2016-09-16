<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/16/2016
 * Time: 2:36 PM
 */

namespace Dot\Rbac\Assertion;

use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class AssertionPluginManager
 * @package Dot\Rbac\Assertion
 */
class AssertionPluginManager extends AbstractPluginManager
{
    protected $instanceOf = AssertionInterface::class;
}