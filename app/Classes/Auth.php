<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\StorageInterface;
use Exception;

class Auth
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function register(string $name, string $email, string $password, string $role = 'Customer'): bool
    {
        if ($this->storage->getUser($email)) {
            throw new Exception('User already exists');
        }
        $user = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role
        ];
        return $this->storage->saveUser($user);
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->storage->getUser($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }
        $_SESSION['user'] = $user;
        return true;
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function check(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }

    public static function checkAdmin(): void
    {
        self::check();
        if ($_SESSION['user']['role'] !== 'Admin') {
            header('Location: dashboard.php');
            exit;
        }
    }

    public static function getCurrentUser(): array
    {
        return $_SESSION['user'];
    }
}
