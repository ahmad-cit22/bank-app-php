<?php

namespace App\Classes;

use App\Interfaces\StorageInterface;
use Exception;

class Transaction
{
    private $storage;
    
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function save(string $userEmail, float $amount, string $type, string $receiverEmail = null): bool
    {
        if ($receiverEmail) {
            $this->validateReceiverEmail($userEmail, $receiverEmail);
        }

        $transaction = [
            'user_email' => $userEmail,
            'receiver_email' => $receiverEmail,
            'amount' => $amount,
            'type' => $type,
            'created_at' => Utility::dateFormat(),
        ];

        return $this->storage->saveTransaction($transaction);
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
            if ($transaction['user_email'] === $userEmail) {
                if ($transaction['type'] === 'deposit') {
                    $balance += $transaction['amount'];
                } else {
                    $balance -= $transaction['amount'];
                }
            } else {
                $balance += $transaction['amount'];
            }
        }

        return $balance;
    }

    public function validateReceiverEmail(string $userEmail, string $receiverEmail): void
    {
        if ($receiverEmail !== null) {
            if ($userEmail === $receiverEmail) {
                throw new Exception('You cannot send money to yourself.');
            }

            if ($this->storage->getUserByEmail($receiverEmail) === null) {
                throw new Exception('User with given email does not exist.');
            }
        }
    }
}