<?php

namespace App\Classes;

use App\Interfaces\StorageInterface;

class User
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getUser(string $email): array
    {
        return $this->storage->getUser($email);
    }

    public function getAllUsers(): array
    {
        return $this->storage->getAllUsers();
    }

    public function getUserTransactions(string $userEmail): array
    {
        return $this->storage->getTransactionsOfUser($userEmail);
    }

    public function getAllTransactions(): array
    {
        return $this->storage->getAllTransactions();
    }

    public function getBalance(string $userEmail): float
    {
        $transactions = $this->storage->getTransactionsOfUser($userEmail);
        $balance = 0;

        if (empty($transactions) || count($transactions) === 0) {
            return $balance;
        }

        foreach ($transactions as $transaction) {
            if ($transaction['type'] === 'credit') {
                $balance += $transaction['amount'];
            } elseif ($transaction['type'] === 'debit') {
                $balance -= $transaction['amount'];
            }
        }

        return $balance;
    }
}
