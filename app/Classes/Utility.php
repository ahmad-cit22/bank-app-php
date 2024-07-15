<?php

namespace App\Classes;

use App\Models\Feedback;

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
     * Formats a timestamp to 'Y-m-d H:i:s' format. If no timestamp is provided, the current time will be used
     *
     * @param string|null $timestamp the timestamp to format
     * @return string the formatted timestamp
     */
    public static function dateFormat(string $timestamp = null): string
    {
        date_default_timezone_set('Asia/Dhaka');

        if ($timestamp) {
            return date('Y-m-d H:i:s', strtotime($timestamp));
        } else {
            return date('Y-m-d H:i:s');
        }
    }
}



