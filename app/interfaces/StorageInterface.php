<?php

namespace App\Interfaces;

interface StorageInterface
{
    public function saveUser(array $user): bool;
    public function getUser(string $email): ?array;
    public function getUserById(int $id): ?array;
    public function getAllUsers(): array;
    public function getLastUserId(): ?int;
    public function saveTransaction(array $transaction): bool;
    public function getTransactionsOfUser(string $userEmail): array;
    public function getAllTransactions(): array;
}
