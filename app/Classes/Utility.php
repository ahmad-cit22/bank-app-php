<?php

namespace App\Classes;

class Utility
{
    /**
     * Constructor for Utility class.
     */
    public function __construct()
    {
        //
    }

    /**
     * Formats a timestamp into a human-readable date and time string.
     *
     * @param string|null $timestamp The timestamp to format (default is current timestamp).
     * @return string The formatted date and time string.
     */
    public static function dateFormat(string $timestamp = null): string
    {
        date_default_timezone_set('Asia/Dhaka');

        if ($timestamp) {
            return date('d M Y, h:i A', strtotime($timestamp));
        } else {
            return date('Y-m-d H:i:s');
        }
    }

    /**
     * Generates initials from a given name.
     *
     * @param string $name The name from which to generate initials.
     * @return string The generated initials.
     */
    public static function generateNameInitials(string $name): string
    {
        $words = explode(" ", $name);
        $initials = "";

        if (count($words) > 1) {
            $initials .= substr($words[0], 0, 1) . substr($words[1], 0, 1);
        } else {
            $initials .= substr($words[0], 0, 2);
        }

        return $initials;
    }

    /**
     * Redirect the user to the previous page or the login page if there is no previous page.
     *
     * @return void
     */
    public static function redirectToBack(): void 
    {
        $back_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

        if ($back_url) {
            header("Location: $back_url");
            exit;
        } else {
            header("Location: /login.php");
            exit;
        }
    }
}



