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
            "active" => 1
        ],
        [
            "id" => 2,
            "email" => "another_unique_email@domain.com",
            "username" => "another_unique_user",
            "password" => "another_secret_password",
            "active" => 1
        ],
        [
            "id" => 3,
            "email" => "not_unique_email@domain.com",
            "username" => "not_unique_user",
            "password" => "asdfghj",
            "active" => 1
        ],
        [
            "id" => 4,
            "email" => "not_unique_email@domain.com",
            "username" => "not_unique_user",
            "password" => "asdfghj",
            "active" => 1
        ],
        [
            "id" => 5,
            "email" => "not_unique_email_active@domain.com",
            "username" => "not_unique_user_active",
            "password" => "asdfghj",
            "active" => 1
        ],
        [
            "id" => 6,
            "email" => "not_unique_email_active@domain.com",
            "username" => "not_unique_user_active",
            "password" => "qwerty",
            "active" => 0
        ],
        [
            "id" => 7,
            "email" => "inactive_user@domain.com",
            "username" => "inactive_user",
            "password" => "uioppo",
            "active" => 0
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
              `active` BIT NOT NULL
            );
        ");
        
        foreach($this->userlist as $u)
        {
            $stmt = $pdo->prepare("INSERT INTO users VALUES "
                    . "(:id, :email, :username, :password_hash, :active)");
            $stmt->bindValue("id", $u["id"]);
            $stmt->bindValue("email", $u["email"]);
            $stmt->bindValue("username", $u["username"]);
            $stmt->bindValue("password_hash", $h->hash($u["password"]));
            $stmt->bindValue("active", (int) $u["active"]);
            $stmt->execute();
        }
        
        return $pdo;
    }
}
