<?php

namespace App\Storage;

use App\Interfaces\StorageInterface;
use PDO;
use PDOException;
use Exception;

class DatabaseStorage implements StorageInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

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
