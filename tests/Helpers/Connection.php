<?php

use Pollus\UserSessionManagement\Models\PasswordHasher;

class Connection
{
    protected $userlist = 
    [
        [
            "id" => 1,
            "email" => "unique_email@domain.com",
            "username" => "unique_user",
            "password" => "secret_password",
            "active" => 1,
            "token" => "123456789123456789",
        ],
        [
            "id" => 2,
            "email" => "another_unique_email@domain.com",
            "username" => "another_unique_user",
            "password" => "another_secret_password",
            "active" => 1,
            "token" => "321654987321654897"
        ],
        [
            "id" => 3,
            "email" => "not_unique_email@domain.com",
            "username" => "not_unique_user",
            "password" => "asdfghj",
            "active" => 1,
            "token" => null
        ],
        [
            "id" => 4,
            "email" => "not_unique_email@domain.com",
            "username" => "not_unique_user",
            "password" => "asdfghj",
            "active" => 1,
            "token" => null
        ],
        [
            "id" => 5,
            "email" => "not_unique_email_active@domain.com",
            "username" => "not_unique_user_active",
            "password" => "asdfghj",
            "active" => 1,
            "token" => null
        ],
        [
            "id" => 6,
            "email" => "not_unique_email_active@domain.com",
            "username" => "not_unique_user_active",
            "password" => "qwerty",
            "active" => 0,
            "token" => null
        ],
        [
            "id" => 7,
            "email" => "inactive_user@domain.com",
            "username" => "inactive_user",
            "password" => "uioppo",
            "active" => 0,
            "token" => null
        ],
    ];
    
    public function get() : PDO
    {
        $h = new PasswordHasher();
        
        $pdo = new PDO("sqlite::memory:", null, null, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);
        $pdo->exec("CREATE TABLE `users` 
            (
              `id` INTEGER PRIMARY KEY AUTOINCREMENT,
              `email` VARCHAR(100) NOT NULL,
              `username` VARCHAR(100) NOT NULL,
              `password_hash` VARCHAR(200) NOT NULL,
              `token` VARCHAR(200),
              `active` BIT NOT NULL
            );
        ");
        
        foreach($this->userlist as $u)
        {
            $stmt = $pdo->prepare("INSERT INTO users VALUES "
                    . "(:id, :email, :username, :password_hash, :token, :active)");
            $stmt->bindValue("id", $u["id"]);
            $stmt->bindValue("email", $u["email"]);
            $stmt->bindValue("username", $u["username"]);
            $stmt->bindValue("password_hash", $h->hash($u["password"]));
            $stmt->bindValue("token", $u["token"]);
            $stmt->bindValue("active", (int) $u["active"]);
            $stmt->execute();
        }
        
        return $pdo;
    }
}
