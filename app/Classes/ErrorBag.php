<?php

namespace App\Classes;

class ErrorBag
{
    private array $errors = [];

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
     * Gets the errors stored in the ErrorBag.
     *
     * @return array The array of errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Adds an error message to the error bag.
     *
     * @param string $key The key of the error.
     * @param string $msg The error message.
     * @return void
     */
    public function addError(string $key, string $msg): void
    {
        $this->errors[$key] = $msg;
    }

    /**
     * Checks if the ErrorBag has any errors.
     *
     * @return bool Returns true if the ErrorBag has errors, false otherwise.
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
