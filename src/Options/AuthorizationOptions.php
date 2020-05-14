<?php
/**
 * @see https://github.com/dotkernel/dot-rbac/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-rbac/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Rbac\Options;

use Laminas\Stdlib\AbstractOptions;

/**
 * Class AuthorizationOptions
 * @package Dot\Rbac\Options
 */
class AuthorizationOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $guestRole = 'guest';

    /**
     * @var array
     */
    protected $assertions = [];

    /**
     * @var array
     */
    protected $roleProvider = [];

    /**
     * AuthorizationOptions constructor.
     * @param array|null|\Traversable $options
     */
    public function __construct($options)
    {
        $this->__strictMode__ = false;
        parent::__construct($options);
    }

    /**
     * @return string
     */
    public function getGuestRole(): string
    {
        return $this->guestRole;
    }

    /**
     * @param string $guestRole
     */
    public function setGuestRole(string $guestRole)
    {
        $this->guestRole = $guestRole;
    }

    /**
     * @return array
     */
    public function getAssertions(): array
    {
        return $this->assertions;
    }

    /**
     * @param array $assertions
     */
    public function setAssertions(array $assertions)
    {
        $this->assertions = $assertions;
    }

    /**
     * @return array
     */
    public function getRoleProvider(): array
    {
        return $this->roleProvider;
    }

    /**
     * @param array $roleProvider
     */
    public function setRoleProvider(array $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }
}
