<?php

declare(strict_types=1);

namespace DotTest\Rbac\TestData;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

use function sprintf;

final class ContainerException extends RuntimeException implements ContainerExceptionInterface
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Service "%s" could not be created.', $id));
    }
}
