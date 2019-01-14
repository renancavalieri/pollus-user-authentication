<?php

use PHPUnit\Framework\TestCase;
use Pollus\UserSessionManagement\Models\PasswordHasher;

class PasswordHasherTest extends TestCase
{
    /**
     * @var PasswordHasher
     */
    protected $hasher;
    
    protected function setUp()
    {
        $this->hasher = new PasswordHasher();
    }
    
    public function testHashing()
    {
       $hash = $this->hasher->hash("123456");
       $this->assertSame(true, $this->hasher->compare("123456", $hash));
    }
    
    public function testColisions()
    {
        $old_hash = null;
        for($i=0; $i<=10; $i++)
        {
            $new_hash = $this->hasher->hash("123456");
            $this->assertNotSame($old_hash, $new_hash);
            $old_hash = $new_hash;
        }
    }
}
