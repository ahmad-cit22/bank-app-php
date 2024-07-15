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

    public function getAllUsers(): array
    {
        return $this->storage->getAllUsers();
    }

    public function getUserTransactions(int $userId): array
    {
        return $this->storage->getTransactionsOfUser($userId);
    }

    public function getBalance(int $userId): float
    {
        $transactions = $this->storage->getTransactionsOfUser($userId);
        $balance = 0;
        foreach ($transactions as $transaction) {
            if ($transaction['type'] === 'deposit') {
                $balance += $transaction['amount'];
            } elseif ($transaction['type'] === 'withdraw') {
                $balance -= $transaction['amount'];
            }
        }
        return $balance;
    }
}
