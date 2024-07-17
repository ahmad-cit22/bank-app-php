<?php

namespace App\Storage;

use App\Interfaces\StorageInterface;
use PDO;
use PDOException;
use Exception;

class DatabaseStorage implements StorageInterface
{
    private PDO $pdo;

    /**
     * Constructor for the DatabaseStorage class.
     *
     * @param PDO $pdo The PDO object to be injected.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    
    /**
     * Saves a user to the database.
     *
     * @param array $user An associative array containing user data:
     *                    - name: The name of the user.
     *                    - email: The email of the user.
     *                    - password: The password of the user.
     *                    - role: The role of the user.
     * @throws Exception If there is an error saving the user.
     * @return bool True if the user is successfully saved, false otherwise.
     */
    public function saveUser(array $user): bool
    {
        try {
            $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $stmt = $this->pdo->prepare($query);

            return $stmt->execute([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'role' => $user['role'],
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error saving user: " . $e->getMessage());
        }
    }

    /**
     * Retrieves a user from the database based on their email.
     *
     * @param string $email The email of the user.
     * @throws PDOException If there is an error executing the database query.
     * @throws Exception If there is an error fetching the user.
     * @return array|null The user data as an associative array, or null if the user is not found.
     */
    public function getUserByEmail(string $email): ?array
    {
        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email]);

            $user = $stmt->fetch();

            return $user ? $user : null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }

    /**
     * Retrieves a user from the database based on their ID.
     *
     * @param int $id The ID of the user.
     * @throws Exception Error fetching user
     * @return array|null The user data as an associative array, or null if the user is not found.
     */
    public function getUserById(int $id): ?array
    {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['id' => $id]);

            $user = $stmt->fetch();

            return $user ? $user : null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }

    /**
     * Retrieves all users from the database excluding those with the 'Admin' role.
     *
     * @throws Exception Error fetching users: If there is an error retrieving the users.
     * @return array The user data as an array of associative arrays.
     */
    public function getAllUsers(): array
    {
        try {
            $query = "SELECT * FROM users WHERE role <> 'Admin'";
            $stmt = $this->pdo->query($query);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
        }
    }

    /**
     * Retrieves the ID of the last user in the database.
     *
     * @return int|null The ID of the last user, or null if no users exist.
     * @throws Exception If there is an error fetching the last user ID.
     */
    public function getLastUserId(): ?int
    {
        try {
            $query = "SELECT id FROM users ORDER BY id DESC LIMIT 1";
            $stmt = $this->pdo->query($query);

            $lastUserId = $stmt->fetchColumn();

            return $lastUserId ? (int) $lastUserId : null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching last user id: " . $e->getMessage());
        }
    }

    /**
     * Saves a transaction to the database.
     *
     * @param array $transaction The transaction data to be saved.
     *        It should contain the following keys:
     *        - user_email: The email of the user.
     *        - receiver_email: The email of the receiver.
     *        - amount: The amount of the transaction.
     *        - type: The type of the transaction.
     *        - created_at: The creation date of the transaction.
     * @throws Exception Error saving transaction: If there is an error saving the transaction.
     * @return bool True if the transaction is successfully saved, false otherwise.
     */
    public function saveTransaction(array $transaction): bool
    {
        try {
            $query = "INSERT INTO transactions (user_email, receiver_email, amount, type, created_at) VALUES (:user_email, :receiver_email, :amount, :type, :created_at)";
            $stmt = $this->pdo->prepare($query);

            return $stmt->execute([
                'user_email' => $transaction['user_email'],
                'receiver_email' => $transaction['receiver_email'],
                'amount' => $transaction['amount'],
                'type' => $transaction['type'],
                'created_at' => $transaction['created_at'],
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error saving transaction: " . $e->getMessage());
        }
    }

    /**
     * Retrieves transactions of a user based on their email.
     *
     * @param string $userEmail The email of the user.
     * @throws Exception Error fetching transactions: If there is an error executing the database query.
     * @return array The transactions as an array of associative arrays.
     */
    public function getTransactionsOfUser(string $userEmail): array
    {
        try {
            $query = "SELECT * FROM transactions WHERE user_email = :user_email OR receiver_email = :receiver_email ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'user_email' => $userEmail,
                'receiver_email' => $userEmail
            ]);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error fetching transactions: " . $e->getMessage());
        }
    }

    /**
     * Retrieves all transactions from the database.
     *
     * @throws Exception Error fetching transactions: If there is an error executing the database query.
     * @return array The transactions as an array of associative arrays.
     */
    public function getAllTransactions(): array
    {
        try {
            $query = "SELECT * FROM transactions ORDER BY created_at DESC";
            $stmt = $this->pdo->query($query);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error fetching transactions: " . $e->getMessage());
        }
    }
}
