<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Storage\FileStorage;
use App\Storage\DatabaseStorage;
use App\Classes\Auth;
use App\Classes\Transaction;
use App\Classes\User;
use App\Interfaces\StorageInterface;

$config = require __DIR__ . '/config.php';

$storage = null;

if ($config['storage'] === 'file') {
    $storage = new FileStorage();
} elseif ($config['storage'] === 'database') {
    $db = $config['db'];

    if ($db['driver'] === 'sqlite') {
        $dsn = "sqlite:{$db['dbname']}";
    } else {
        $dsn = "{$db['driver']}:host={$db['host']};dbname={$db['dbname']}";
    }

    $pdo = new PDO($dsn, $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $storage = new DatabaseStorage($pdo);
}

if (!$storage instanceof StorageInterface) {
    throw new Exception('Invalid storage configuration.');
}

$auth = new Auth($storage);
$user = new User($storage);
$transaction = new Transaction($storage);

return [
    'auth' => $auth,
    'user' => $user,
    'transaction' => $transaction
];
