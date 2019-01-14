<?php

use PHPUnit\Framework\TestCase;
use Pollus\UserSessionManagement\Models\UserRepository;
use Pollus\UserSessionManagement\Exceptions\UserRepositoryException;

class UserRepositoryTest extends TestCase
{
    /**
     * @var PDO
     */
    protected $pdo;
    
    /**
     * @var UserRepository
     */
    protected $repo;
    
    /**
     * @var Connection
     */
    protected $helper;

    protected function setUp()
    {
        require_once(__DIR__."/Helpers/Connection.php");
        $this->helper = new Connection();
        $this->pdo = $this->helper->get();
        $this->repo = new UserRepository($this->pdo);
    }
    
    public function testGetByEmail()
    {
        $user = $this->repo->getUserByEmail("unique_email@domain.com");
        $this->assertSame("unique_user", $user->getUsername());
        
        $inactive_user = $this->repo->getUserByEmail("inactive_user@domain.com");
        $this->assertSame("inactive_user", $inactive_user->getUsername());
    }
    
    public function testGetByEmailShouldThrowExceptionDuplicated()
    {
        $this->expectException(UserRepositoryException::class);
        $user = $this->repo->getUserByEmail("not_unique_email@domain.com");
    }
    
    public function testGetByEmailShouldThrowExceptionNotFound()
    {
        $this->expectException(UserRepositoryException::class);
        $user = $this->repo->getUserByEmail("noemail@noemail.com");
    }
    
    public function getByUsername()
    {
        $user = $this->repo->getUserByUsername("unique_user");
        $this->assertSame("unique_email@domain.com", $user->getEmail());
        
        $inactive_user = $this->repo->getUserByUsername("inactive_user");
        $this->assertSame("inactive_user@domain.com", $inactive_user->getEmail());
    }
    
    public function getByUsernameShouldThrowExceptionDuplicated()
    {
        $this->expectException(UserRepositoryException::class);
        $user = $this->repo->getUserByUsername("not_unique_user");
    }
    
    public function getByUsernameShouldThrowExceptionNotFound()
    {
        $this->expectException(UserRepositoryException::class);
        $user = $this->repo->getUserByUsername("nouser");
    }
    
    public function getById()
    {
        $user = $this->repo->getUserById(1);
        $this->assertSame("unique_email@domain.com", $user->getEmail());
    }
}
