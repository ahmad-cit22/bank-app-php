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

    public function save(string $userEmail, string $receiverEmail = null, float $amount, string $type): bool
    {
        $transaction = [
            'user_email' => $userEmail,
            'receiver_email' => $receiverEmail,
            'amount' => $amount,
            'type' => $type,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->storage->saveTransaction($transaction);
    }
}