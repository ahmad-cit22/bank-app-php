<?php

namespace App\Storage;

use App\Interfaces\StorageInterface;
use PDO;

class DatabaseStorage implements StorageInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveUser(array $user): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        return $stmt->execute([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
            'role' => $user['role'],
        ]);
    }

    public function getUser(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getUserById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getAllUsers(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastUserId(): ?int
    {
        $stmt = $this->pdo->query('SELECT MAX(id) FROM users');
        $lastUserId = $stmt->fetchColumn();
        return $lastUserId ?: 0;
    }

    public function saveTransaction(array $transaction): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO transactions (user_id, type, amount, created_at) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$transaction['user_id'], $transaction['type'], $transaction['amount'], $transaction['created_at']]);
    }

    public function getTransactionsOfUser(string $userEmail): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM transactions WHERE email = ?');
        $stmt->execute([$userEmail]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTransactions(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM transactions');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
