<?php

namespace App\Storage;

use App\Interfaces\StorageInterface;
use Exception;

class FileStorage implements StorageInterface
{
    private $dataDir;
    private $userFile;
    private $transactionFile;

    private array $usersData = [];
    private array $transactionsData = [];

    public function __construct()
    {
        $this->dataDir = dirname(__DIR__, 2) . '/data';
        $this->userFile = $this->dataDir . '/users.txt';
        $this->transactionFile = $this->dataDir . '/transactions.txt';

        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0777, true);
        }
        
        if (!file_exists($this->userFile)) {
            touch($this->userFile);
        }
        if (!file_exists($this->transactionFile)) {
            touch($this->transactionFile);
        }

        $this->loadData();
    }

    public function saveUser(array $user): bool
    {
        $data = json_encode($user);

        file_put_contents($this->userFile, $data . PHP_EOL, FILE_APPEND);

        return true;
    }

    public function getUser(string $email): ?array
    {
        if ($this->usersData && count($this->usersData) > 0) {
            foreach ($this->usersData as $userData) {
                $user = json_decode($userData, true);

                if ($user['email'] === $email) {
                    return $user;
                }
            }
        }

        return null;
    }

    public function getAllUsers(): array
    {
        $users = [];

        if ($this->usersData && count($this->usersData) > 0) {
            foreach ($this->usersData as $userData) {
                $user = json_decode($userData, true);

                if ($user['role'] !== 'Admin') {
                    $users[] = $user;
                }
            }
        }

        return $users;
    }

    public function getLastUserId(): ?int
    {
        $users = [];

        if ($this->usersData && count($this->usersData) > 0) {
            foreach ($this->usersData as $userData) {
                $user = json_decode($userData, true);
                    $users[] = $user;
            }
        }

        return $users ? $users[count($users) - 1]['id'] : 0;
    }

    public function saveTransaction(array $transaction): bool
    {
        $data = json_encode($transaction);

        file_put_contents($this->transactionFile, $data . PHP_EOL, FILE_APPEND);

        return true;
    }

    public function getTransactionsOfUser(string $userEmail): array
    {
        $transactions = [];

        if ($this->transactionsData && count($this->transactionsData) > 0) {
            foreach ($this->transactionsData as $transactionData) {
                $transaction = json_decode($transactionData, true);

                if ($transaction['email'] === $userEmail) {
                    $transactions[] = $transaction;
                }
            }
        }

        return $transactions;
    }

    public function getAllTransactions(): array
    {
        $transactions = [];

        if ($this->transactionsData && count($this->transactionsData) > 0) {
            foreach ($this->transactionsData as $transactionData) {
                $transaction = json_decode($transactionData, true);

                $transactions[] = $transaction;
            }
        }

        return $transactions;
    }

    private function loadData(): void
    {
        if (!file_exists($this->userFile) || !is_readable($this->userFile)) {
            throw new Exception("Error Loading Data File!");
        }
        
        if (!file_exists($this->transactionFile) || !is_readable($this->transactionFile)) {
            throw new Exception("Error Loading Data File!");
        }

        $this->usersData = file($this->userFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $this->transactionsData = file($this->transactionFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}
