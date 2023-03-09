## Start the project
- run `composer install`
- create a file called `.env` from `.env.example`
- configure database connection
- generate the key `php artisan key:generate`
- generate jwt secret `php artisan jwt:secret`
- run `php artisan migrate`
- start the server with `php artisan serve`

## Run the tests
- run `php artisan test`
