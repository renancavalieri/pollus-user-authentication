<?php declare(strict_types=1);

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement\Models;

use Pollus\UserSessionManagement\Models\UserSessionInterface;
use Pollus\SessionWrapper\Session;
use Pollus\SessionWrapper\SessionInterface;
use Pollus\UserSessionManagement\Exceptions\SessionException;

class UserSession implements UserSessionInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;
    
    public function __construct(?SessionInterface $session)
    {
        if ($session === null)
        {
            $this->session = new Session();
        }
        else
        {
            $this->session = $session;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function setUserLoggedId($id)
    {
        if ($this->session->status() !== PHP_SESSION_ACTIVE)
        {
           throw new SessionException("Session is not started");
        }
        $this->session->set("user_logged", $id);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserLoggedId()
    {
        if ($this->session->status() !== PHP_SESSION_ACTIVE)
        {
           throw new SessionException("Session is not started");
        }
        
        $user_id = $this->session->get("user_logged");
        
        if ($user_id === null || $user_id === false)
        {
            return null;
        }
        return $user_id;
    }

}
