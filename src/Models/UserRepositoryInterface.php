<?php declare(strict_types=1);

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement\Models;

use Pollus\UserSessionManagement\Exceptions\UserRepositoryException;
use Pollus\UserSessionManagement\Models\UserInterface;

interface UserRepositoryInterface
{
    /**
     * Gets the user by ID
     * 
     * @param int|string $id
     * @return UserInterface
     * @throws UserRepositoryException if not found
     */
    public function getUserById($id) : UserInterface;
    
    /**
     * Gets the user by email
     * 
     * @param string $email
     * @return UserInterface
     * @throws UserRepositoryException if not found
     */
    public function getUserByEmail(string $email) : UserInterface;
    
    /**
     * Gets the user by username
     * 
     * @param string $username
     * @return UserInterface
     * @throws UserRepositoryException if not found
     */
    public function getUserByUsername(string $username) : UserInterface;
}
