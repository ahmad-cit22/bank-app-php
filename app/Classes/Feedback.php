<?php

namespace App\Classes;

use Exception;

class Feedback
{
    /**
     * Constructor for the Feedback class.
     *
     * @param string $recipientEmail The email of the recipient
     * @param string $feedback The feedback message
     */
    public function __construct(private string $recipientEmail, private string $feedback) 
    {
        $this->recipientEmail = $recipientEmail;
        $this->feedback = $feedback;
    }

    /**
     * Saves the feedback data to a file.
     *
     * This function creates a JSON object containing the recipient email, feedback message, and creation date.
     * It then appends the JSON object to the 'data/feedbacks.txt' file.
     *
     * @return void
     */
    public function saveData(): void
    {
        $feedbackData = [
            'recipientEmail' => $this->recipientEmail,
            'feedback' => $this->feedback,
            'createdAt' => Utility::dateFormat()
        ];

        $data = json_encode($feedbackData);

        file_put_contents('data/feedbacks.txt', $data . PHP_EOL, FILE_APPEND);
    }
}