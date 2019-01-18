<?php declare(strict_types=1);

/**
 * User Session Management
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\UserSessionManagement\Models;

use PDO;
use Pollus\UserSessionManagement\Models\User;
use Pollus\UserSessionManagement\Exceptions\UserRepositoryException;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var PDO
     */
    protected $pdo;
    
    /**
     * @var string
     */
    protected $table;
    
    /**
     * @var string
     */
    protected $userClass;
    
    /**
     * @param PDO $pdo
     * @param string $table
     */
    public function __construct(PDO $pdo, string $table = "users", string $userClass = User::class)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->userClass = $userClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserByEmail(string $email): UserInterface
    {
        return $this->query("email", $email);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserById($id): UserInterface
    {
        return $this->query("id", $id);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserByUsername(string $username): UserInterface
    {
        return $this->query("username", $username);
    }
    
    /**
     * {@inheritDoc}
     */
    public function updateUserHash($user_id, string $hash) : bool
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET password_hash = :password_hash WHERE id = :id");
        $stmt->bindValue("password_hash", $hash);
        $stmt->bindValue("id", $user_id);
        return $stmt->execute();
    }
    
    /**
     * {@inheritDoc}
     */
    public function updateUserToken($user_id, string $token) : bool
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET token = :token WHERE id = :id");
        $stmt->bindValue("token", $token);
        $stmt->bindValue("id", $user_id);
        return $stmt->execute();
    }

    /**
     * Performs a query on database
     * 
     * @param string $field - ** This value is not parameterized or escaped **
     * @param mixed $value
     * @return UserInterface
     * @throws UserRepositoryException
     */
    protected function query(string $field, $value) : UserInterface
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$field} = :field");
        $stmt->bindValue("field", $value);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_CLASS, $this->userClass);    
        
        if ($user === null || isset($user[0]) === false)
            throw new UserRepositoryException("User not found");
        
        if (count($user) > 1)
            throw new UserRepositoryException("More than one row found. Expected only one");
        
        return $user[0];
    }

}
