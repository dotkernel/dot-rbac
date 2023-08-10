<?php

declare(strict_types=1);

namespace DotTest\Rbac\Factory;

use Dot\Rbac\Factory\AuthenticationIdentityProviderFactory;
use Dot\Rbac\Identity\AuthenticationIdentityProvider;
use Laminas\Authentication\AuthenticationService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthenticationIdentityProviderFactoryTest extends TestCase
{
    private ContainerInterface|MockObject $container;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateService(): void
    {
        $this->container->method('get')
            ->willReturnMap([
                [AuthenticationService::class, $this->createMock(AuthenticationService::class)],
            ]);
        $requestedName = AuthenticationIdentityProvider::class;

        $result = (new AuthenticationIdentityProviderFactory())($this->container, $requestedName);
        $this->assertInstanceOf(AuthenticationIdentityProvider::class, $result);
    }
}
