<?php

namespace App\Classes;

use App\Interfaces\StorageInterface;
use Exception;

class Transaction
{
    private $storage;


    /**
     * Constructor for the Transaction class.
     *
     * @param StorageInterface $storage The storage interface to be injected.
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Saves a transaction with the given user email, amount, type, and optional receiver email.
     *
     * @param string $userEmail The email of the user initiating the transaction.
     * @param float $amount The amount of the transaction.
     * @param string $type The type of the transaction.
     * @param string|null $receiverEmail The email of the receiver, if applicable.
     * @return bool Returns true if the transaction is successfully saved, false otherwise.
     */
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

    /**
     * Retrieves the transactions of a specific user based on the user's email.
     *
     * @param string $userEmail The email of the user for whom transactions are to be retrieved.
     * @return array The transactions of the specified user.
     */
    public function getUserTransactions(string $userEmail): array
    {
        return $this->storage->getTransactionsOfUser($userEmail);
    }

    /**
     * Retrieves all transactions.
     *
     * @return array The transactions of all users.
     */
    public function getAllTransactions(): array
    {
        return $this->storage->getAllTransactions();
    }

    /**
     * Retrieves the balance for a specific user based on their transactions.
     *
     * @param string $userEmail The email of the user for whom the balance is to be retrieved.
     * @return float The balance of the specified user.
     */
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

    /**
     * Validates the receiver email for a transaction.
     *
     * @param string $userEmail The email of the user initiating the transaction.
     * @param string $receiverEmail The email of the receiver, if applicable.
     * @throws Exception If the receiver email is the same as the user email or the receiver email does not exist in the database/storage.
     * @return void
     */
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