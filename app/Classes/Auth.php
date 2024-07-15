<?php

declare(strict_types=1);

namespace App\Classes;

use Exception;

class Auth
{
    /**
     * Constructs a new instance of the class.
     *
     * This constructor does not have any parameters.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Registers a new user with the provided name, email, and hashed password.
     *
     * @param string $name The name of the user.
     * @param string $email The email address of the user.
     * @param string $hashedPassword The hashed password of the user.
     * @return void
     */
    public function register(string $name, string $email, string $hashedPassword): void
    {
        $user = new User($name, $email, $hashedPassword);
        $user->saveData();

        Message::flash('success', 'Your account has been created successfully! You can now log in.');
        header('Location: login.php');
        exit;
    }

    /**
     * Logs in a user with the provided email and password.
     *
     * @param string $email The email address of the user.
     * @param string $password The password of the user.
     * @return bool Returns true if the user is successfully logged in, false otherwise.
     */
    public function login(string $email, string $password): bool
    {
        $usersData = self::getUsersDataJson();
        foreach ($usersData as $user) {
            $user = json_decode($user, true);

            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                return true;
            }
        }
        return false;
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

    /**
     * Checks if a user is currently logged in.
     *
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Checks if a user is logged in. If not, redirects to the login page.
     *
     * @throws No exceptions are thrown by this function.
     * @return void
     */
    public static function check(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        } 
    }
    
    /**
     * Retrieves the current user from the session.
     *
     * @return array The user data stored in the session.
     */
    public static function getCurrentUser(): array
    {
        return $_SESSION['user'];
    }
    
    /**
     * Finds a user by their feedback string.
     *
     * @param string $feedbackString The feedback string to search for.
     * @return array The user data associated with the feedback string, or an empty array if not found.
     */
    public static function findUser(string $feedbackString): array
    {
        $usersData = self::getUsersDataJson();

        foreach ($usersData as $user) {
            $user = json_decode($user, true);
            if ($user['feedbackString'] === $feedbackString) {
                return $user;
            }
        }

        return [];
    }

    /**
     * Retrieves the user data from the specified file.
     *
     * @return array The user data from the file.
     * @throws Exception If the file is not found or not readable.
     */
    private static function getUsersDataJson(): array
    {
        $filePath = 'data/users.txt';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Error Loading Data File!");
        }

        $usersData = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        return $usersData;
    }
    
}