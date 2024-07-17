<?php

return [
    'storage' => 'database', // can be 'file' or 'database'
    'db' => [
        'driver' => 'mysql', // can be 'mysql', 'pgsql', 'sqlite', etc.
        'host' => '127.0.0.1',
        'dbname' => 'banking_app_php',
        'username' => 'root',
        'password' => '', 
    ],
    'admin_creation_password' => 'banking-app-admin', // password for admin creation
];
