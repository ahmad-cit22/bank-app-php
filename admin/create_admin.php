<?php

use App\Classes\Input;

$config = require __DIR__ . '/../config.php';
$dependencies = require __DIR__ . '/../bootstrap.php';
$auth = $dependencies['auth'];

echo "\n";
echo "Create Admin\n";
echo "------------------\n\n";

echo "Enter Admin Creation Password: ";
$adminPassword = trim(fgets(STDIN));

if ($adminPassword !== $config['admin_creation_password']) {
    echo "\n";
    echo "Error: Invalid admin creation password.\n";
    exit;
}

echo "\n";
echo "Enter Name: ";
$name = trim(fgets(STDIN));

echo "Enter Email: ";
$email = trim(fgets(STDIN));

echo "Enter Password: ";
$password = fgets(STDIN);

echo "Confirm Password: ";
$confirmPassword = fgets(STDIN);

$name = Input::sanitizeInput($name);
$email = Input::sanitizeInput($email);

if (!Input::isNameValid($name)) {
    echo "\n";
    echo "Error: Invalid name.\n";
    exit;
}

if (!Input::isEmailValid($email)) {
    echo "\n";
    echo "Error: Invalid email.\n";
    exit;
}

if (!Input::isPasswordValid($password)) {
    echo "\n";
    echo "Error: Invalid password.\n";
    exit;
}

if (!Input::isConfirmPasswordValid($password, $confirmPassword)) {
    echo "\n";
    echo "Error: Passwords do not match.\n";
    exit;
}

echo "\n";
echo "Creating Admin...\n";

try {
    $auth->register($name, $email, $password, 'Admin');
    echo "\n";
    echo "Admin created successfully.\n";
    echo "Email: " . $email . "\n";
    exit;
} catch (Exception $e) {
    echo "\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit;
}
