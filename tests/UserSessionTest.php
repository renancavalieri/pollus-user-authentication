<?php

use Pollus\FakeSession\FakeSession;
use Pollus\UserSessionManagement\Models\UserSession;

class UserSessionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UserSession
     */
    public $usession;
    
    protected function setUp()
    {
        $fake_session = new FakeSession();
        $this->usession = new UserSession($fake_session);
        $fake_session->start();
    }
    
    public function testUserSession()
    {
        $this->usession->setUserLoggedId(10);
        $this->assertSame(10, $this->usession->getUserLoggedId());
    }
    
    public function testUserSessionCanBeNull()
    {
        $this->usession->setUserLoggedId(null);
        $this->assertSame(null, $this->usession->getUserLoggedId());
    }
    
    public function testUserSessionCanBeString()
    {
        $this->usession->setUserLoggedId("myid");
        $this->assertSame("myid", $this->usession->getUserLoggedId());
    }
}
