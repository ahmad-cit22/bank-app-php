<?php

declare(strict_types=1);
session_start();

$dependencies = require __DIR__ . '/bootstrap.php';
$auth = $dependencies['auth'];
$userClass = $dependencies['user'];

use App\Classes\Input;
use App\Classes\ErrorBag;
use App\Classes\Message;

if ($auth->isLoggedIn()) {
  $auth->redirectAsPerRole();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errorBag = new ErrorBag();

  $name = Input::get('name');
  $email = Input::get('email');
  $password = Input::get('password');
  $confirmPassword = Input::get('confirm_password');

  if (!Input::isNameValid($name)) {
    $errorBag->addError('name', 'Please enter a valid name using only letters and spaces.');
  }

  if (!Input::isEmailValid($email)) {
    $errorBag->addError('email', 'Please enter a valid email address.');
  }

  if (!Input::isPasswordValid($password)) {
    $errorBag->addError('password', 'Please enter a password using at least one capital letter, one lowercase letter, one number, and a minimum of 8 characters.');
  }

  if (!Input::isConfirmPasswordValid($password, $confirmPassword)) {
    $errorBag->addError('confirm_password', 'Please enter the same password as the one above.');
  }

  if ($errorBag->hasErrors()) {
    $errors = $errorBag->getErrors();
  } else {
    try {
      $name = Input::sanitizeInput($name);
      $email = Input::sanitizeInput($email);
      $hashedPassword = Input::hashPassword($password);

      $auth->register($name, $email, $hashedPassword);
      Message::flash('success', 'Registration successful! You can now log in.');
      header('Location: login.php');
      exit;
    } catch (Exception $e) {
      Message::flash('error', $e->getMessage());
    }
  }
}

?>

<!DOCTYPE html>
<html class="h-full bg-white" lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    * {
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont,
        'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans',
        'Helvetica Neue', sans-serif;
    }
  </style>

  <title>Create A New Account</title>
</head>

<body class="h-full bg-slate-100">
  <div class="flex flex-col justify-center min-h-full py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-2xl font-bold leading-9 tracking-tight text-center text-gray-900">
        Create A New Account
      </h2>
    </div>

    <?php $message = Message::flash('error');
    if ($message) : ?>
      <div class="mt-3 bg-red-100 border border-red-200 text-sm text-red-700 rounded-lg p-3 text-center" role="alert">
        <span class="font-bold"><?= $message; ?></span>
      </div>
    <?php endif; ?>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
      <div class="px-6 py-12 bg-white shadow sm:rounded-lg sm:px-12">
        <form class="space-y-6" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
          <div>
            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
            <div class="mt-2">
              <input id="name" name="name" type="text" value="<?= $name ?? '' ?>" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>

            <?php if (isset($errors['name'])) : ?>
              <p class="text-sm text-red-700 mt-2" id="name-error"><?= $errors['name']; ?></p>
            <?php endif; ?>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
            <div class="mt-2">
              <input id="email" name="email" type="email" autocomplete="email" value="<?= $email ?? '' ?>" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>

            <?php if (isset($errors['email'])) : ?>
              <p class="text-sm text-red-700 mt-2" id="email-error"><?= $errors['email']; ?></p>
            <?php endif; ?>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
            <div class="mt-2">
              <input id="password" name="password" type="password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>

            <?php if (isset($errors['password'])) : ?>
              <p class="text-sm text-red-700 mt-2" id="password-error"><?= $errors['password']; ?></p>
            <?php endif; ?>
          </div>

          <div>
            <label for="confirm_password" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
            <div class="mt-2">
              <input id="confirm_password" name="confirm_password" type="password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>

            <?php if (isset($errors['confirm_password'])) : ?>
              <p class="text-sm text-red-700 mt-2" id="password-error"><?= $errors['confirm_password']; ?></p>
            <?php endif; ?>
          </div>

          <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
              Register
            </button>
          </div>
        </form>
      </div>

      <p class="mt-10 text-sm text-center text-gray-500">
        Already a customer?
        <a href="./login.php" class="font-semibold leading-6 text-emerald-600 hover:text-emerald-500">Sign-in</a>
      </p>
    </div>
  </div>
</body>

</html>