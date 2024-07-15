<?php

namespace App\Interfaces;

interface StorageInterface
{
    public function saveUser(array $user): bool;
    public function getUser(string $email): ?array;
    public function getAllUsers(): array;
    public function saveTransaction(array $transaction): bool;
    public function getTransactionsOfUser(int $userId): array;
    public function getAllTransactions(): array;
}
