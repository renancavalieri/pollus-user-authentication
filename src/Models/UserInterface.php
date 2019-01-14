<?php declare(strict_types=1);

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement\Models;
use Pollus\UserSessionManagement\Exceptions\UserException;

interface UserInterface
{
    /**
     * Returns the User ID
     * 
     * @throws UserException if the user ID is null or empty
     */
    public function getId();
    
    /**
     * Returns the username
     * 
     * @return string
     * @throws UserException if the username is null or empty
     */
    public function getUsername() : string;
    
    /**
     * Returns the user email
     * 
     * @return string
     * @throws UserException if the email is null or empty
     */
    public function getEmail() : string;
    
    /**
     * Sets the password hash
     * 
     * @param string $password_hash
     * @return string
     */
    public function setPasswordHash(string $password_hash) : string;
    
    /**
     * Returns the password hash
     * 
     * @return string
     * @throws UserException if the password hash is null or empty
     */
    public function getPasswordHash() : string;
    
    /**
     * Sets the active status
     * 
     * Setting to FALSE will prevent the user from login and will logout all
     * of its sessions
     * 
     * @param bool $active
     */
    public function setActive(bool $active);
    
    /**
     * Returns TRUE if the user is active
     * 
     * @return bool
     */
    public function isActive() : bool;
}