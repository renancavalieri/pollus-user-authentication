# Pollus User Session Management

This library provides a simple and flexible authentication and session management.

**Setup:**

```composer require pollus/user-session-management```

Creates the following database scheme (or implements the **UserSessionInterface** to your own scheme):

```sql
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
);
```

## Examples
**Checking if someone is logged**

```php
use Pollus\UserSessionManagement\Models\UserRepository;
use Pollus\UserSessionManagement\Models\PasswordHasher;
use Pollus\UserSessionManagement\Models\UserSession;
use Pollus\UserSessionManagement\Exceptions\AuthenticationException;
use Pollus\UserSessionManagement\UserSessionManagement;

session_start();
        
$manager = new UserSessionManagement 
(
    new PasswordHasher(),
    new UserRepository($pdo),
    new UserSession()
);

$user = $manager->getCurrentUser();

if ($user === null)
{
    // not logged
}
else
{
    // Logger User ID
    $user->getId();
}
```

**Login form**

```php
use Pollus\UserSessionManagement\Models\UserRepository;
use Pollus\UserSessionManagement\Models\PasswordHasher;
use Pollus\UserSessionManagement\Models\UserSession;
use Pollus\UserSessionManagement\Exceptions\AuthenticationException;
use Pollus\UserSessionManagement\UserSessionManagement;

session_start();
        
$manager = new UserSessionManagement 
(
    new PasswordHasher(),
    new UserRepository($pdo),
    new UserSession()
);

[..] Gets the user input here [..]

try
{
    // Login by username
    $success = $manager->loginByUsername($email, $password);

    // Or login by email
    $success = $manager->loginUserByEmail($email, $password);

    // Or both! (email will be checked only if username fails)
    $success = $manager->loginUserByEmail($username_or_email, $password);

    if ($success === false)
    {
        // Wrong username, email and password, or duplicated username or email
    }
    else
    {
        // User logged
        $user = $manager->getCurrentUser(); 
        $user->getId();
    }
}
catch(AuthenticationException $ex)
{
    // Login is valid but the user is inactive
}
```

# MIT License

Copyright (c) 2019 Renan Cavalieri

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
