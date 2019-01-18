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
        
    /**
     * Updates the password hash
     * 
     * @param type $user_id
     * @param string $hash
     * @return bool
     */
    public function updateUserHash($user_id, string $hash) : bool;
    
    /**
     * Updates the user token
     * 
     * @param type $user_id
     * @param string $token
     * @return bool
     */
    public function updateUserToken($user_id, string $token) : bool;
}
