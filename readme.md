# Bank Application

This is a simple banking application built with PHP having features for both 'Admin' and 'Customer' users. The application supports both file and database storage methods, which can be configured based on your needs.

## Features

### Admin Features
- **View All Transactions:** Access a comprehensive list of all transactions made by every user in the system.
- **Search Transactions by User:** Search and view transactions for a specific user by using their email address.
- **View All Registered Customers:** Access a list of all customers registered in the system.

### Customer Features
- **Registration:** Customers can create an account by providing their name, email, and password.
- **Login:** Customers can log in using their registered email and password.
- **View Transactions:** Customers can see a list of all transactions associated with their account.
- **Deposit Money:** Customers can deposit money into their account.
- **Withdraw Money:** Customers can withdraw money from their account.
- **Transfer Money:** Customers can transfer money to another customer by specifying the recipient's email address.
- **Check Account Balance:** Customers can view the current balance of their account.

## Requirements

- PHP 7.4 or higher
- Composer (for managing dependencies)
- A web server (e.g., Apache, Nginx) or PHP built-in server
- MySQL (if using database storage)

## Installation

1. **Clone the Repository:**

    ```bash
    git clone https://github.com/ahmad-cit22/bank-app-php.git
    cd bank-app-php
    ```

2. **Install Dependencies:**

    Make sure you have Composer installed, then run:

    ```bash
    composer install
    ```

3. **Configure the Application:**

    Modify the configuration settings in the `config.php` file according to your environment:

    - Choose between `file` and `database` storage methods.
    - Set up database credentials if using the database method.
    - Configure other application settings as needed.

4. **Create an Admin User:**

    Use the CLI script to create a new admin user:

    ```bash
    php create_admin/create_admin.php
    ```

5. **Run the Application:**

    You can run the application using a web server or PHP's built-in server:

    ```bash
    php -S localhost:8000 -t public/
    ```

    The application will be accessible at `http://localhost:8000`.


## Contributing

If you'd like to contribute, please fork the repository and use a feature branch. Pull requests are warmly welcome.

Thank you.
