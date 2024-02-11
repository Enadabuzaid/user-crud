
<h1 align="center">
Laravel User CRUD
</h1>

<p>
This Laravel project provides a complete CRUD (Create, Read, Update, Delete) system for managing users, integrating a dedicated UserService for business logic encapsulation and comprehensive testing to ensure robustness and reliability. The system allows for adding, viewing, updating, and deleting users, with additional functionalities including soft deletes, photo uploads, and event-driven actions for user activities. It utilizes Laravel's robust features like Eloquent ORM, migrations, seeders, events, listeners, and queues, alongside testing with PHPUnit to cover both unit and feature tests ensuring high code quality.
</p>

### Prerequisites
- Docker (if using Docker for the project)
- PHP >= 8.0
- Composer
- MySQL (if running outside Docker)


### Setup Instructions

1- Cloning the Project
```
git clone https://github.com/Enadabuzaid/user-crud.git
cd user-crud
```

2.1 Running with Docker (Using Laravel Sail)
- Copy Environment File
    ```
    cp .env.example .env
    ```
- Install Dependencies
    ```
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php80-composer:latest \
        composer install --ignore-platform-reqs
    ```
- Start Docker Containers
  - If you haven't installed Sail as a global command:
  ```
    ./vendor/bin/sail up -d
  ```
  - If you have Sail globally:
  ```
    sail up -d
  ```
- Generate Application Key
  ```
    sail artisan key:generate
  ```
- Run Migrations and Seeders
  ```
    sail artisan migrate --seed
  ```
2.2 Running without Docker (Directly on a System with MySQL)
- Copy Environment File
    ```
    cp .env.example .env
    ```
  Edit .env to set your database connection details under the DB_* settings.


- Install Dependencies
    ```
    composer install
    ```
- Generate Application Key
  ```
    php artisan key:generate
  ```
- Run Migrations and Seeders
  ```
    php artisan migrate --seed
  ```

### Running Tests
- With Laravel Sail:
  ```
    sail artisan test
  ```
- Without Docker:
  ```
    php artisan test
  ```


## Default User Accounts
After seeding, use these credentials to log in:

- Admin Account
    - Email: admin@admin.com
    - Password: password

    
- User Account
  - Email: user@user.com
  - Password: password

