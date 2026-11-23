<?php

declare(strict_types=1);

namespace Dot\Rbac\Factory;

use Dot\Rbac\Rbac;
use Psr\Container\ContainerInterface;

class RbacFactory
{
    public function __invoke(ContainerInterface $container): Rbac
    {
        return new Rbac();
    }
}
