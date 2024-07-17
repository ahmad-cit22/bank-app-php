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

    /**
     * Constructor for FileStorage class.
     *
     * Initializes data directory, user file, and transaction file.
     * Creates data directory if it doesn't exist.
     * Creates user file and transaction file if they don't exist.
     * Calls the 'loadData' method to load existing data.
     */
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

    /**
     * Saves a user to the user file.
     *
     * @param array $user An associative array containing user data:
     *        - name: The name of the user.
     *        - email: The email of the user.
     *        - password: The password of the user.
     *        - role: The role of the user.
     * @return bool True if the user is successfully saved, false otherwise.
     */
    public function saveUser(array $user): bool
    {
        $data = json_encode($user);

        file_put_contents($this->userFile, $data . PHP_EOL, FILE_APPEND);

        return true;
    }

    /**
     * Retrieves a user from the users data array based on their email.
     *
     * @param string $email The email of the user to search for.
     * @return array|null The user data array if found, or null if not found.
     */
    public function getUserByEmail(string $email): ?array
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
    
    /**
     * Retrieves a user from the users data array based on their id.
     *
     * @param int $id The id of the user to search for.
     * @return array|null The user data array if found, or null if not found.
     */
    public function getUserById(int $id): ?array
    {
        if ($this->usersData && count($this->usersData) > 0) {
            foreach ($this->usersData as $userData) {
                $user = json_decode($userData, true);

                if ($user['id'] === $id) {
                    return $user;
                }
            }
        }

        return null;
    }

    /**
     * Retrieves all users from the users data array excluding admin users.
     *
     * @return array An array of user data arrays, excluding admin users.
     */
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

    /**
     * Retrieves the ID of the last user in the users data array.
     *
     * @return int|null The ID of the last user, or null if the users data array is empty.
     */
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

    /**
     * Saves a transaction to the transactions file.
     *
     * @param array $transaction The transaction data to be saved.
     *        It should contain the following keys:
     *        - user_email: The email of the user.
     *        - receiver_email: The email of the receiver (if applicable).
     *        - amount: The amount of the transaction.
     *        - type: The type of the transaction.
     *        - created_at: The creation date of the transaction.
     * @return bool True if the transaction is successfully saved, false otherwise.
     */
    public function saveTransaction(array $transaction): bool
    {
        $data = json_encode($transaction);

        file_put_contents($this->transactionFile, $data . PHP_EOL, FILE_APPEND);

        return true;
    }

    /**
     * Retrieves transactions of a specific user based on user email.
     *
     * @param string $userEmail The email of the user to retrieve transactions for.
     * @return array An array of transactions related to the specified user.
     */
    public function getTransactionsOfUser(string $userEmail): array
    {
        $transactions = [];

        if ($this->transactionsData && count($this->transactionsData) > 0) {
            foreach ($this->transactionsData as $transactionData) {
                $transaction = json_decode($transactionData, true);

                if ($transaction['user_email'] === $userEmail || $transaction['receiver_email'] === $userEmail) {
                    $transactions[] = $transaction;
                }
            }
        }

        $orderedTransactions = array_reverse($transactions);

        return $orderedTransactions;
    }

    /**
     * Retrieves all transactions from the transactions data array.
     *
     * @return array An array of all transactions, ordered in reverse chronological order.
     */
    public function getAllTransactions(): array
    {
        $transactions = [];

        if ($this->transactionsData && count($this->transactionsData) > 0) {
            foreach ($this->transactionsData as $transactionData) {
                $transaction = json_decode($transactionData, true);

                $transactions[] = $transaction;
            }
        }

        $orderedTransactions = array_reverse($transactions);

        return $orderedTransactions;
    }

    
    /**
     * Loads data from the user file and transaction file.
     *
     * This function checks if the user file and transaction file exist and are readable.
     * If either file is missing or not readable, an Exception is thrown with the message "Error Loading Data File!".
     * 
     * If both files exist and are readable, the function reads the contents of the files into the `usersData` and `transactionsData` properties.
     * 
     * @throws Exception if either the user file or transaction file is missing or not readable.
     * @return void
     */
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
