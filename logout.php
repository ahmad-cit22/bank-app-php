<?php

declare(strict_types=1);
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Classes\Auth;

if (Auth::isLoggedIn()) {
    Auth::logout();
} else {
    header('Location: login.php');
    exit;
}

