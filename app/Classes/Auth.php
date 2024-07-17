<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\StorageInterface;
use Exception;

class Auth
{
    private $storage;

    /**
     * Constructor for the Auth class.
     *
     * @param StorageInterface $storage The storage interface to be injected.
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Register a new user with the provided name, email, hashed password, and role.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $hashedPassword The hashed password of the user.
     * @param string $role The role of the user (default is 'Customer').
     * @throws Exception User already exists.
     * @return bool Returns true if the user is successfully registered, false otherwise.
     */
    public function register(string $name, string $email, string $hashedPassword, string $role = 'Customer'): bool
    {
        if ($this->storage->getUserByEmail($email)) {
            throw new Exception('User already exists');
        }

        $lastUserId = $this->storage->getLastUserId();

        $user = [
            'id' => $lastUserId + 1,
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ];

        return $this->storage->saveUser($user);
    }

    /**
     * Logs in a user with the provided email and password.
     *
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @return bool Returns true if the user is successfully logged in, false otherwise.
     */
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

    /**
     * Checks if a user is logged in based on the presence of 'user' in the session.
     *
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Checks if a user is logged in and redirects to the login page if not.
     *
     * @return void
     */
    public static function check(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ../../login.php');
            exit;
        }
    }

    /**
     * Checks if the user is an admin. If not, redirects to the customer dashboard.
     */
    public static function checkAdmin(): void
    {
        self::check();
        if ($_SESSION['user']['role'] !== 'Admin') {
            header('Location: resources/customer/customer_dashboard.php');
            exit;
        }
    }

    /**
     * Retrieves the current user from the session or an empty array if not set.
     *
     * @return array
     */
    public static function getCurrentUser(): array
    {
        return $_SESSION['user'] ?? [];
    }

    /**
     * Redirects the user based on their role.
     *
     * This function retrieves the current user's role and redirects them to the appropriate page based on their role.
     * If the user is an admin, they are redirected to the 'resources/admin/customers.php' page.
     * If the user is a customer, they are redirected to the 'resources/customer/customer_dashboard.php' page.
     *
     * @return void
     */
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
