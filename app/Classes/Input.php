<?php

namespace App\Classes;

class Input
{

    /**
     * Constructor for the Input class.
     *
     * This constructor initializes the object and sets up any necessary resources.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieves the value of a specific key from the $_POST superglobal.
     *
     * @param string $key The key to retrieve the value for.
     * @return mixed|null The value associated with the key, or null if the key is not set.
     */
    public static function get(string $key): mixed
    {
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    /**
     * Checks if the given name is valid.
     *
     * This function checks if the given name is empty or does not match the regular expression pattern
     * '/^[a-zA-Z\s]+$/', which allows only alphabetic characters and spaces. If the name is not valid,
     * the function returns false; otherwise, it returns true.
     *
     * @param string $name The name to be checked.
     * @return bool Returns true if the name is valid, false otherwise.
     */
    public static function isNameValid(string $name): bool
    {
        if (empty($name) || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
            return false;
        }
        return true;
    }

    /**
     * Checks if the given email is valid.
     *
     * This function checks if the given email is empty or does not match the email format using FILTER_VALIDATE_EMAIL.
     * If the email is not valid, the function returns false; otherwise, it returns true.
     *
     * @param string $email The email to be checked.
     * @return bool Returns true if the email is valid, false otherwise.
     */
    public static function isEmailValid(string $email): bool
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * Validates the strength of a password.
     *
     * @param string $password The password to be checked.
     * @return bool Returns true if the password is valid, false otherwise.
     */
    public static function isPasswordValid(string $password): bool
    {
        if (empty($password) || strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return false;
        }
        return true;
    }

    /**
     * Checks if the given confirm password is valid.
     *
     * This function compares the given password and confirm password.
     * If the confirm password is empty or does not match the password,
     * it returns false. Otherwise, it returns true.
     *
     * @param string $password The password to be compared.
     * @param string $confirmPassword The confirm password to be compared.
     * @return bool Returns true if the confirm password is valid, false otherwise.
     */
    public static function isConfirmPasswordValid(string $password, string $confirmPassword): bool
    {
        if (empty($confirmPassword) || $password !== $confirmPassword) {
            return false;
        }
        return true;
    }

    /**
     * Sanitizes the input by removing HTML special characters and slashes,
     * and trimming any leading or trailing whitespace.
     *
     * @param string $input The input to be sanitized.
     * @return string The sanitized input.
     */
    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    /**
     * Hashes a password using the default algorithm.
     *
     * @param string $password The password to be hashed.
     * @return string The hashed password.
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}


