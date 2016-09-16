<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 5/21/2016
 * Time: 2:32 PM
 */

namespace Dot\Rbac\Role;

use Dot\Rbac\Exception\RuntimeException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

class RoleProviderPluginManager extends AbstractPluginManager
{
    protected $factories = [
        InMemoryRoleProvider::class => InvokableFactory::class,
    ];

    public function validate($instance)
    {
        if($instance instanceof RoleProviderInterface) {
            return;
        }

        throw new RuntimeException(sprintf(
            'Role provider must be an instance of "%s", but "%s" was given',
            RoleProviderInterface::class,
            is_object($instance) ? get_class($instance) : gettype($instance)
        ));
    }
}