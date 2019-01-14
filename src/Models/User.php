<?php declare(strict_types=1);

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement\Models;

use Pollus\UserSessionManagement\Models\UserInterface;
use Pollus\UserSessionManagement\Exceptions\UserException;

class User implements UserInterface
{
    protected $id;
    protected $email;
    protected $username;
    protected $password_hash;
    protected $active = true;

    /**
     * {@inheritDoc}
     */
    public function getEmail(): string
    {
        if ($this->email === null || $this->email === "")
        {
            throw new UserException("User e-mail is empty");
        }
        return $this->email;
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->id === null || $this->id === "")
        {
            throw new UserException("User ID is empty");
        }
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getPasswordHash(): string
    {
        if ($this->password_hash === null || $this->password_hash === "")
        {
            throw new UserException("User password hash is empty");
        }
        return $this->password_hash;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername(): string
    {
        if ($this->username === null || $this->username === "")
        {
            throw new UserException("Username is empty");
        }
        return $this->username;
    }

    /**
     * {@inheritDoc}
     */
    public function setPasswordHash(string $password_hash): string
    {
        $this->password_hash = $password_hash;
    }

    /**
     * {@inheritDoc}
     */
    public function isActive(): bool
    {
        return (bool) $this->active;
    }

    /**
     * {@inheritDoc}
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

}
