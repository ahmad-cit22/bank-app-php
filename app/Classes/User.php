<?php

namespace App\Classes;

use App\Interfaces\StorageInterface;

class User
{
    private $storage;

    /**
     * Constructor for the User class.
     *
     * @param StorageInterface $storage The storage interface to be injected.
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Retrieves a user by their ID from the storage.
     *
     * @param int $id The ID of the user to be retrieved.
     * @return array|null The user details if found, null otherwise.
     */

    public function getUserById(int $id): ?array
    {
        return $this->storage->getUserById($id);
    }
    
    /**
     * Retrieves a user by their email from the storage.
     *
     * @param string $email The email of the user to be retrieved.
     * @return array|null The user details if found, null otherwise.
     */
    public function getUserByEmail(string $email): ?array
    {
        return $this->storage->getUserByEmail($email);
    }

    /**
     * Retrieves all users from the storage.
     *
     * @return array The list of all users.
     */
    public function getAllUsers(): array
    {
        return $this->storage->getAllUsers();
    }
}
