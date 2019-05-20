# Property - API

## Description
A simple tool to manage and keep track of property. Like most web services, it consists of two major parts:
- An Admin backend for managing applications and rented property
- A mobile API for prospective clients to interact with the app

### Setup and Configuration
-  Install dependencies including Laravel Passport and Laravel Auditing 
    ~~~
    composer install
    ~~~

-  Make configurations in the .env file as with any Laravel application.

-  Generate new App key and run migrations. Default Continents (7) will be seeded in the database.
    ~~~
    php artisan key:generate
    php artisan migrate --seed
    ~~~

    The last command should have Laravel Passport equally installed for API authentication.
    Otherwise, install it manually by running:
    ~~~
    php artisan passport:install
    ~~~

    At this point, the app is pretty ready to run