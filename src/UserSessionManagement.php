<?php

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement;

use Pollus\UserSessionManagement\Models\UserSessionInterface;
use Pollus\UserSessionManagement\Models\User;
use Pollus\UserSessionManagement\Models\UserRepositoryInterface;
use Pollus\UserSessionManagement\Exceptions\UserRepositoryException;
use Pollus\UserSessionManagement\Exceptions\AuthenticationException;
use Pollus\UserSessionManagement\Exceptions\SessionException;
use Pollus\UserSessionManagement\Models\PasswordHasherInterface;

class UserSessionManagement
{   
    /**
     * @var UserSessionInterface
     */
    protected $session;
    
    /**
     * @var UserRepositoryInterface
     */
    protected $repository;
    
    /**
     * @var PasswordHasherInterface
     */
    protected $hasher;
    
    /**
     * @param PasswordHasherInterface $hasher
     * @param UserRepositoryInterface $repository
     * @param UserSessionInterface $session
     */
    public function __construct(PasswordHasherInterface $hasher, UserRepositoryInterface $repository, UserSessionInterface $session)
    {
        $this->session = $session;
        $this->hasher = $hasher;
        $this->repository = $repository;
    }
    
    /**
     * Get the current logged user
     * 
     * @return User|null - NULL when no one is logged.
     * @throws AuthenticationException - When the user is inactive or not found
     * @throws SessionException - When the session is not started
     */
    public function getCurrentUser() : ?User
    {
        $user_id = $this->session->getUserLoggedId();
        
        if ($user_id === null) 
        {
            return null;
        }
        try
        {
            $user = $this->repository->getUserById($user_id);    
            
            if ($user->isActive() === false)
            {
                throw new AuthenticationException("User is inactive");
            }    
            
            return $user;
        } 
        catch (UserRepositoryException $ex) 
        {
            throw new AuthenticationException($ex->getMessage());
        }
    }
    
    /**
     * Sets the current logged user (without any password verification)
     * 
     * @param User $user
     * @throws AuthenticationException if the user is inactive
     */
    public function setCurrentUser(User $user)
    {
        if ($user->isActive() === false)
        {
            throw new AuthenticationException("User is inactive");
        }
        
        $this->session->setUserLoggedId($user->getId());
    }
    
    /**
     * Logouts the current user
     * 
     * This method doesn't destroy the session ID nor regenerate it.
     */
    public function logout()
    {
        $this->session->setUserLoggedId(null);
    }
    
    /**
     * Perform login routine using user e-mail address and password
     * 
     * @param string $email
     * @param string $password
     * 
     * @return bool - TRUE on success, FALSE on wrong email and/or password
     */
    public function loginUserByEmail(string $email, string $password) : bool
    {
        return $this->performLogin(false, true, $email, $password);
    }
    
    /**
     * Perform login routine using username and password
     * 
     * @param string $username
     * @param string $password
     * 
     * @throws UserException if the user doesn't have a password hash
     * @throws AuthenticationException if the user is inactive
     * 
     * @return bool - TRUE on success, FALSE on wrong email and/or password
     */
    public function loginUserByUsername(string $username, string $password)
    {
        return $this->performLogin(true, false, $username, $password);
    }
    
    /**
     * Perform login routine using username or e-mail address, and password
     * 
     * This method will lookup for email only if the username was not found.
     * 
     * @param string $username_or_email
     * @param string $password
     * 
     * @throws UserException if the user doesn't have a password hash
     * @throws AuthenticationException if the user is inactive
     * 
     * @return bool - TRUE on success, FALSE on wrong username/email and/or 
     * password
     */
    public function loginUserByUsernameOrEmail(string $username_or_email, string $password) : bool
    {
        return $this->performLogin(true, true, $username_or_email, $password);
    }
    
    /**
     * Gets the repository object
     * 
     * This method is intended to be used only for tests
     * 
     * @return UserRepositoryInterface
     */
    public function getRepositoryObject() : UserRepositoryInterface
    {
        return $this->repository;
    }
    
    /**
     * Gets the session object
     * 
     * This method is intended to be used only for tests
     * 
     * @return UserSessionInterface
     */
    public function getSessionObject() : UserSessionInterface
    {
        return $this->session;
    }
    
    /**
     * Gets the password hasher object
     * 
     * This method is intended to be used only for tests
     * 
     * @return PasswordHasherInterface
     */
    public function getHasherObject() : PasswordHasherInterface
    {
        return $this->hasher;
    }
    
    /**
     * Perform login routine
     *
     * This method will lookup for email only if the username was not found.
     * 
     * @param bool $useUsername - Lookup for username
     * @param bool $useEmail - Lookup for email address
     * @param string $username_or_email - Username or email (input)
     * @param string $password - User password (input)
     * 
     * @throws UserException if the user doesn't have a password hash
     * @throws AuthenticationException if the user is inactive
     * 
     * @return bool - TRUE on success, FALSE on wrong username/email and/or 
     * password
     */
    protected function performLogin(bool $useUsername, bool $useEmail, string $username_or_email, string $password)
    {
        $user = null;
        
        if ($useUsername === true)
        {
            try { $user = $this->repository->getUserByUsername($username_or_email); } 
            catch (UserRepositoryException $ex) {}
        }
        
        if ($useEmail === true && $user === null)
        {
            try { $user = $this->repository->getUserByEmail($username_or_email); } 
            catch (UserRepositoryException $ex) {}
        }
        
        if ($user === null) return false;
        
        if ($this->hasher->compare($password, $user->getPasswordHash()) === true)
        {
            $this->setCurrentUser($user);
            return true;
        }
        
        return false;    
    }
}