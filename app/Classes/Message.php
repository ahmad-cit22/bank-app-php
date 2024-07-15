<?php

namespace App\Classes;

class Message
{
    /**
     * Constructor for the Message class.
     */
    public function __construct()
    {
        //
    }

    /**
     * Flashes a message of a specified type to the session for display.
     *
     * @param string $type The type of the message.
     * @param string|null $message The message to be flashed. If null, the function will retrieve the flashed message of the specified type from the session.
     * @return string|null The flashed message of the specified type, or null if no message is found.
     */
    public static function flash(string $type, string $message = null): ?string
    {
        if ($message) {
            $_SESSION['flash'][$type] = $message;
            return null;
        }

        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        
        return $message;
    }
}
