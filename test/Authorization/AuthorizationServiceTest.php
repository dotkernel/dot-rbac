<?php

declare(strict_types=1);

namespace DotTest\Rbac\Authorization;

use Dot\Rbac\Assertion\Factory;
use Dot\Rbac\Authorization\AuthorizationService;
use Dot\Rbac\Exception\RuntimeException;
use Dot\Rbac\Rbac;
use Dot\Rbac\RbacInterface;
use Dot\Rbac\Role\RoleServiceInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthorizationServiceTest extends TestCase
{
    public AuthorizationService $authorizationService;
    public Rbac|MockObject $rbac;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->rbac           = $this->createMock(RbacInterface::class);
        $roleServiceInterface = $this->createMock(RoleServiceInterface::class);
        $factory              = $this->createMock(Factory::class);

        $this->authorizationService = new AuthorizationService($this->rbac, $roleServiceInterface, $factory);
    }

    public function testAddAssertion(): void
    {
        $permission = 'testPermission';
        $assertion  = ['testAssertion'];

        $this->authorizationService->addAssertion($permission, $assertion);
        $this->assertTrue($this->authorizationService->hasAssertion('testPermission'));
    }

    public function testAddAssertionThrowException(): void
    {
        $permission = 'testPermission';
        $assertion  = 'testAssertion';
        $this->expectException(RuntimeException::class);
        $this->authorizationService->addAssertion($permission, $assertion);
    }

    public function testIsGranted(): void
    {
        $this->rbac->expects($this->once())->method('isGranted')->willReturn(true);

        $permission = 'testPermission';
        $roles      = ['testRole'];
        $context    = 'context';

        $result = $this->authorizationService->isGranted($permission, $roles, $context);
        $this->assertTrue($result);
    }

    public function testIsGrantedEmptyRoles(): void
    {
        $permission = 'testPermission';

        $result = $this->authorizationService->isGranted($permission);
        $this->assertFalse($result);
    }

    public function testRbacIsGrantedFalse(): void
    {
        $permission = 'testPermission';
        $roles      = ['testRole'];

        $result = $this->authorizationService->isGranted($permission, $roles);
        $this->assertFalse($result);
    }
}
