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
    
    /**
     * @var string
     */
    protected $key;
    
    /**
     * @var string
     */
    protected $token_key;


    public function __construct(?SessionInterface $session, string $key = "user_logged", string $token_key = "token")
    {
        $this->key = $key;
        $this->token_key = $token_key;
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
    public function setUserLoggedId($id, ?string $token = null)
    {
        if ($this->session->status() !== PHP_SESSION_ACTIVE)
        {
           throw new SessionException("Session is not started");
        }
        $this->session->set($this->key, $id);
        $this->session->set($this->token_key, $token);
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
        
        $user_id = $this->session->get($this->key);
        
        if ($user_id === null || $user_id === false)
        {
            return null;
        }
        return $user_id;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserLoggedToken(): ?string 
    {
        if ($this->getUserLoggedId() === null)
        {
            return null;
        }
        return $this->session->get($this->token_key);
    }
}
