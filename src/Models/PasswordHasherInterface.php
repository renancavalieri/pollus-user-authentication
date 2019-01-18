<?php declare(strict_types=1);

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement\Models;

interface PasswordHasherInterface
{
    /**
     * Compares a password with given hash
     * 
     * @param string $password
     * @param string $hash
     * 
     * @return bool 
     */
    public function compare(string $password, string $hash) : bool;
    
    /**
     * Creates a password hash
     * 
     * @param string $password
     * @return string
     */
    public function hash(string $password) : string;
    
    /**
     * Generates a random user token
     * 
     * @return string
     */
    public function token() : string;
}
