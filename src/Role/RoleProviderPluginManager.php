<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace Dot\Rbac\Role;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * Class RoleProviderPluginManager
 * @package Dot\Rbac\Role
 */
class RoleProviderPluginManager extends AbstractPluginManager
{
    protected $instanceOf = RoleProviderInterface::class;

    protected $factories = [
        InMemoryRoleProvider::class => InvokableFactory::class,
    ];
}