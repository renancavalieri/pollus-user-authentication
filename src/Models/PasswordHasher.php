<?php declare(strict_types=1);

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement\Models;

class PasswordHasher implements PasswordHasherInterface
{
    /**
     * {@inheritDoc}
     */
    public function compare(string $password, string $hash): bool
    {
        return (password_verify($password, $hash));
    }

    /**
     * {@inheritDoc}
     */
    public function hash(string $password): string
    {
        return (password_hash($password, PASSWORD_BCRYPT));
    }
}
