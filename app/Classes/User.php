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

    public function getUserById(int $id): ?array
    {
        return $this->storage->getUserById($id);
    }
    
    public function getUserByEmail(string $email): ?array
    {
        return $this->storage->getUserByEmail($email);
    }

    public function getAllUsers(): array
    {
        return $this->storage->getAllUsers();
    }
}
