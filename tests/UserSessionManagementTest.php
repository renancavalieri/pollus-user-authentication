<?php

use PHPUnit\Framework\TestCase;
use Pollus\UserSessionManagement\Exceptions\UserRepositoryException;
use Pollus\UserSessionManagement\Models\UserRepository;
use Pollus\UserSessionManagement\Models\PasswordHasher;
use Pollus\UserSessionManagement\Models\UserSession;
use Pollus\UserSessionManagement\Exceptions\AuthenticationException;
use Pollus\UserSessionManagement\UserSessionManagement;
use Pollus\FakeSession\FakeSession;

class UserSessionManagementTest extends TestCase
{
    /**
     * @var PDO
     */
    protected $pdo;
    
    /**
     * @var Connection
     */
    protected $helper;

    protected function setUp()
    {
        require_once(__DIR__."/Helpers/Connection.php");
    }
    
    protected function getManagerMockObject() : UserSessionManagement
    {
        $fake_session = new FakeSession();
        $fake_session->start();
        return new UserSessionManagement
        (
            new PasswordHasher(),
            new UserRepository( (new Connection())->get() ),
            new UserSession( $fake_session )
        );
    }


    public function testValidLoginRoutineByEmail()
    {
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByEmail("unique_email@domain.com", "secret_password");
        $this->assertSame(true, $result);
        $repo = $manager->getRepositoryObject();
        $this->assertSame($repo->getUserById(1)->getId(), $manager->getCurrentUser()->getId());
    }
    
    public function testInvalidPasswordLoginRoutineByEmail()
    {
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByEmail("unique_email@domain.com", "wrong_password");
        $this->assertSame(false, $result);
        $this->assertSame(null, $manager->getCurrentUser());
    }
    
    public function testInvalidEmailLoginRoutineByEmail()
    {
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByEmail("wrongemail@domain.com", "secret_password");
        $this->assertSame(false, $result);
        $this->assertSame(null, $manager->getCurrentUser());
    }
    
    public function testInvalidDuplicatedLoginRoutineByEmail()
    {
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByEmail("not_unique_email@domain.com", "asdfghj");
        $this->assertSame(false, $result);
        $this->assertSame(null, $manager->getCurrentUser());
    }
    
    public function testInactiveLoginRoutineByEmail()
    {
        $this->expectException(AuthenticationException::class);
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByEmail("inactive_user@domain.com", "uioppo");
    }
    
    public function testValidLoginRoutineByUsername()
    {
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsername("unique_user", "secret_password");
        $this->assertSame(true, $result);
        $repo = $manager->getRepositoryObject();
        $this->assertSame($repo->getUserById(1)->getId(), $manager->getCurrentUser()->getId());
    }
    
    public function testInvalidPasswordLoginRoutineByUsername()
    {
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsername("unique_user", "wrong_password");
        $this->assertSame(false, $result);
        $this->assertSame(null, $manager->getCurrentUser());
    }
    
    public function testInvalidUsernameLoginRoutineByUsername()
    {
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsername("wrong user", "secret_password");
        $this->assertSame(false, $result);
        $this->assertSame(null, $manager->getCurrentUser());
    }
    
    public function testInvalidDuplicatedLoginRoutineByUsername()
    {
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsername("not_unique_user", "asdfghj");
        $this->assertSame(false, $result);
        $this->assertSame(null, $manager->getCurrentUser());
    }
    
    public function testInactiveLoginRoutineByUsername()
    {
        $this->expectException(AuthenticationException::class);
        $manager = $this->getManagerMockObject();
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsername("inactive_user", "uioppo");
    }
    
    public function testValidLoginRoutineByUsernameOrEmail()
    {
        $manager = $this->getManagerMockObject();
        $repo = $manager->getRepositoryObject();
        
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsernameOrEmail("unique_email@domain.com", "secret_password");
        $this->assertSame(true, $result);
        $this->assertSame($repo->getUserById(1)->getId(), $manager->getCurrentUser()->getId());
        
        $manager->logout();
        
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsernameOrEmail("unique_user", "secret_password");
        $this->assertSame(true, $result);
        $this->assertSame($repo->getUserById(1)->getId(), $manager->getCurrentUser()->getId());
    }
    
    public function testInvalidLoginRoutineByUsernameOrEmail()
    {
        $manager = $this->getManagerMockObject();
        $repo = $manager->getRepositoryObject();
        
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsernameOrEmail("unique_email@domain.com", "wrong pass");
        $this->assertSame(false, $result);
        $this->assertSame(null, $manager->getCurrentUser());
        
        $manager->logout();
        
        $this->assertSame(null, $manager->getCurrentUser());
        $result = $manager->loginUserByUsernameOrEmail("unique_user", "wrong pass");
        $this->assertSame(false, $result);
        $this->assertSame(null, $manager->getCurrentUser());
    }
}
