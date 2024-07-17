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
        if ($this->storage->getUserByEmail($email)) {
            throw new Exception('User already exists');
        }

        $lastUserId = $this->storage->getLastUserId();

        $user = [
            'id' => $lastUserId + 1,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];

        return $this->storage->saveUser($user);
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->storage->getUserByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $user['name_initial'] = Utility::generateNameInitials($user['name']);

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
            header('Location: ../../login.php');
            exit;
        }
    }

    public static function checkAdmin(): void
    {
        self::check();
        if ($_SESSION['user']['role'] !== 'Admin') {
            header('Location: resources/customer/customer_dashboard.php');
            exit;
        }
    }

    public static function getCurrentUser(): array
    {
        return $_SESSION['user'] ?? [];
    }

    public function redirectAsPerRole(): void
    {
        $user = $this->getCurrentUser();
        $userRole = $user['role'];

        if ($userRole === 'Admin') {
            header('Location: resources/admin/customers.php');
            exit;
        } elseif ($userRole === 'Customer') {
            header('Location: resources/customer/customer_dashboard.php');
            exit;
        }
    }

    /**
     * Logs out the current user by unsetting the session, destroying it if active, 
     * clearing the session cookie, and redirecting to the login page.
     *
     * @return void
     */
    public static function logout(): void
    {
        unset($_SESSION);

        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        setcookie(session_name(), '', time() - 3600, '/');

        header("Location: login.php");
        exit;
    }
}
