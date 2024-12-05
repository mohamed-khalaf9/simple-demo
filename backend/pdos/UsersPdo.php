<?php


class UsersPdo{

    private $pdo;

    function __construct($pdo)
    {
        $this->pdo=$pdo;
        
    }

    public function createUser($name, $email, $password) {
        // Check if the email already exists in the database
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
    
        // If the email already exists, return false
        if ($stmt->rowCount() > 0) {
            return false;
        }
    
        // Insert the user into the database (no hashing for password in your case)
        $stmt = $this->pdo->prepare("INSERT INTO users (email, name, password) VALUES (:email, :name, :password)");
        $stmt->execute(['email' => $email, 'name' => $name, 'password' => $password]);
    
        return true;  // Return true if signup is successful
    }

    public function validateUser($email, $password): bool {
        try {
            // Query to check for a matching email and password
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
            $stmt->execute(['email' => $email, 'password' => $password]);
    
            // Return true if a match is found, otherwise false
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Log or handle the exception if necessary
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function getUserIdByEmail($email): ?int {
        try {
            // Query to fetch the user ID
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Return the user ID if found, otherwise null
            return $result ? (int) $result['id'] : null;
        } catch (PDOException $e) {
            // Log or handle the exception
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }
    
    
    
}























?>