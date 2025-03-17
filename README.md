Requirements
PHP >= 8.0
Composer
Node.js and npm
A supported database (e.g., MySQL)

Installation
Follow these steps to set up the project on your local machine:

1. Install PHP Dependencies
   Install the required PHP packages via Composer:

```bash
composer install
```

2. Environment Setup Copy the example environment file and generate a new application key:

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

Make sure to update your .env file with the correct database and other configuration details.

3Run Migrations and Seeders
Reset the database and run all migrations with seeders to populate sample data:

```bash
php artisan migrate:fresh --seed
```

4. Compile Frontend Assets
   Install Node.js dependencies and compile the assets:

```bash
npm install
```

```bash
npm run dev
```

6. Start the Application
   Start the Laravel development server:

```bash
php artisan serve
```

Your application will be accessible at http://127.0.0.1:8000.

7. Process the Queue  - DON'T NEED THIS (didn't have time to implement)
   In a separate terminal window, run the queue worker to process queued jobs:

```bash
php artisan queue:work
```

Admin Login
Access the admin panel by navigating to:

http://127.0.0.1:8000/admin/login

Use the following credentials:

Email: admin@admin.com
Password: adminadmin



TODO:: 
redirect after checkout ,
user can decrease to 0 and should back to main page,
session messages,
jobs
