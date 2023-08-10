<?php

declare(strict_types=1);

namespace Dot\Rbac\Options;

use Laminas\Stdlib\AbstractOptions;

class AuthorizationOptions extends AbstractOptions
{
    protected string $guestRole   = 'guest';
    protected array $assertions   = [];
    protected array $roleProvider = [];

    public function __construct(?array $options)
    {
        $this->__strictMode__ = false;
        parent::__construct($options);
    }

    public function getGuestRole(): string
    {
        return $this->guestRole;
    }

    public function setGuestRole(string $guestRole)
    {
        $this->guestRole = $guestRole;
    }

    public function getAssertions(): array
    {
        return $this->assertions;
    }

    public function setAssertions(array $assertions)
    {
        $this->assertions = $assertions;
    }

    public function getRoleProvider(): array
    {
        return $this->roleProvider;
    }

    public function setRoleProvider(array $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }
}
