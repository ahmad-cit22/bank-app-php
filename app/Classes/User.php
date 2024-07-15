<?php

namespace App\Classes;

use Exception;

class User
{
    private string $feedbackString;
    private array $usersData = [];

    /**
     * Constructs a new instance of the User class.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @param bool $isNew (optional) Whether the user is new or not. Defaults to true.
     * @throws None
     * @return void
     */
    public function __construct(
        private string $name,
        private string $email,
        private string $password,
        private bool $isNew = true
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->isNew = $isNew;

        if ($this->isNew) {
            $this->loadData();
        }

        $this->setFeedbackString();
    }

    /**
     * Sets a unique feedback string for the user if the user is new.
     *
     * @throws None
     * @return void
     */
    private function setFeedbackString(): void
    {
        if ($this->isNew) {
            do {
                $uniqueStr = substr(md5(uniqid(true)), 0, 6);

                $isUnique = true;

                if ($this->usersData && count($this->usersData) > 0) {
                    foreach ($this->usersData as $userData) {
                        $user = json_decode($userData, true);

                        if ($user['feedbackString'] === $uniqueStr) {
                            $isUnique = false;
                            break;
                        }
                    }
                }
            } while (!$isUnique);

            $this->feedbackString = $uniqueStr;
        }

        return;
    }

    /**
     * Checks if a user exists in the list of user data.
     *
     * @return bool Returns true if the user exists, false otherwise.
     */
    private function userExists(): bool
    {
        if ($this->usersData && count($this->usersData) > 0) {
            foreach ($this->usersData as $userData) {
                $user = json_decode($userData, true);

                if ($user['email'] === $this->email) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Retrieves the feedbacks for the current user from the data file.
     *
     * @throws Exception if the data file cannot be read or does not exist
     * @return array An array of feedbacks for the current user
     */
    public function getFeedbacks(): array
    {
        $filePath = 'data/feedbacks.txt';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Error Loading Data File!");
        }

        $feedbacksData = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $userFeedbacks = [];

        foreach ($feedbacksData as $feedback) {
            $feedback = json_decode($feedback, true);

            if ($feedback['recipientEmail'] === $this->email) {
                $userFeedbacks[] = $feedback;
            }
        }

        return $userFeedbacks;
    }

    /**
     * Save user data to a file after checking if the user already exists.
     * 
     * @throws Exception User Already Exists with this Email!
     */
    public function saveData(): void
    {
        if ($this->userExists()) {
            throw new Exception("User Already Exists with this Email!");
        }

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'feedbackString' => $this->feedbackString,
            'createdAt' => date('Y-m-d H:i:s')
        ];

        $data = json_encode($userData);

        file_put_contents('data/users.txt', $data . PHP_EOL, FILE_APPEND);
    }

    /**
     * Loads the data from the specified file path into the usersData property.
     *
     * @throws Exception Error Loading Data File!
     * @return void
     */
    private function loadData(): void
    {
        $filePath = 'data/users.txt';

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Error Loading Data File!");
        }

        $this->usersData = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}
