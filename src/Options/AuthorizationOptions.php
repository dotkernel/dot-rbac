<?php

declare(strict_types=1);

namespace Dot\Rbac\Options;

use Laminas\Stdlib\AbstractOptions;

/**
 * @extends AbstractOptions<mixed>
 */
class AuthorizationOptions extends AbstractOptions
{
    protected string $guestRole = 'guest';
    /** @var array<array-key, mixed> */
    protected array $assertions = [];
    /** @var array<string, mixed> */
    protected array $roleProvider = [];

    /**
     * @param array<string, mixed>|null $options
     */
    public function __construct(?array $options)
    {
        $this->__strictMode__ = false;
        parent::__construct($options);
    }

    public function getGuestRole(): string
    {
        return $this->guestRole;
    }

    public function setGuestRole(string $guestRole): void
    {
        $this->guestRole = $guestRole;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getAssertions(): array
    {
        return $this->assertions;
    }

    /**
     * @param array<array-key, mixed> $assertions
     */
    public function setAssertions(array $assertions): void
    {
        $this->assertions = $assertions;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRoleProvider(): array
    {
        return $this->roleProvider;
    }

    /**
     * @param array<string, mixed> $roleProvider
     */
    public function setRoleProvider(array $roleProvider): void
    {
        $this->roleProvider = $roleProvider;
    }
}
