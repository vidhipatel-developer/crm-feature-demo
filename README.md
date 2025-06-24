# VIDHI PATEL
# Project Setup Guide (CRM Feature)

This document provides a step-by-step guide to get the project up and running on your local machine. Follow these
instructions carefully to ensure a smooth setup.

## Prerequisites

Before you begin, ensure you have the following software installed on your system:

- PHP: Version 8.2 or higher (check your composer.json for specific requirements).

- Composer: A dependency manager for PHP.

- MySQL: A relational database management system.

## Installation Steps

Follow these steps in the given order to set up your project:

#### 1. Install Project Dependencies

First, navigate to your project's root directory in your terminal and install all PHP dependencies using Composer:

```
composer install
```

#### 2. Database Setup (MySQL)

You need to create a new MySQL database for this project.

Open your MySQL client (e.g., phpMyAdmin, MySQL Workbench, or a terminal client).

Execute the following SQL command to create a new database. Replace your_database_name with your desired database name:

```
CREATE DATABASE your_database_name;
```

#### 3. Setup Environment File

Laravel uses an .env file to manage environment-specific configurations (like database credentials, API keys, etc.).

Copy the example environment file and rename it to .env:

```
cp .env.example .env
```

Open the newly created .env file in a text editor.

#### 4. Configure Database Connection in .env

In your .env file, locate the database section and update the following variables with your MySQL credentials and the
database name you created in Step 2:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name  # <-- Enter the name of your database here
DB_USERNAME=your_mysql_username # <-- Enter your MySQL username
DB_PASSWORD=your_mysql_password # <-- Enter your MySQL password
```

#### 5. Generate Application Key

Laravel requires an application key for security purposes. This key is used for session encryption and other sensitive
data.

Run the following Artisan command to generate the key:

```
php artisan key:generate
```

This command will automatically update your .env file with a new APP_KEY.

#### 6. Set Up Proper APP_URL

In your .env file, ensure APP_URL is correctly set. For local development, this is typically http://localhost
or http://127.0.0.1. If you are serving the project from a subdirectory, include it.

```
APP_URL=http://localhost:8000 # Or your custom local URL, e.g., http://127.0.0.1:8000
```

Note: If you plan to use php artisan serve, the default port is 8000, so http://localhost:8000 is often appropriate.

### 7. Run Database Migrations

Once your database connection is configured, run the migrations to create the necessary tables in your database:

```
php artisan migrate
```

#### 8. Seed the Database (Optional, but recommended)

If your project includes seeders to populate the database with dummy data (e.g., for initial testing or demonstration),
run them:

```
php artisan db:seed
```

#### 9. Start the Development Server

Finally, to run your Laravel application, start the built-in development server:

```
php artisan serve
```

This command will typically make your application accessible at `http://127.0.0.1:8000` (or `http://localhost:8000`).
You can open this URL in your web browser.

You should now have the project fully set up and running on your local environment. If you encounter any issues,
double-check each step and consult the Laravel documentation or project-specific instructions if available.
