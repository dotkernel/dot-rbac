<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
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
