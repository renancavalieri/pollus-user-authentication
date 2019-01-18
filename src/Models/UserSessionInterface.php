<?php declare(strict_types=1);

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement\Models;

use Pollus\UserSessionManagement\Exceptions\SessionException;

interface UserSessionInterface
{
    /**
     * Set the user logged ID.
     * 
     * @param type $id
     * @param string|null $token
     * @throws SessionException if session session is not active
     */
    public function setUserLoggedId($id, ?string $token);
    
    /**
     * Returns the logged user ID or NULL when none.
     * 
     * @throws SessionException if session session is not active
     */
    public function getUserLoggedId();
    
    /**
     * Returns the authentication token from the current user
     * 
     * @return string|null
     */
    public function getUserLoggedToken() : ?string;
}
