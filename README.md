
## Vehicle Registration GraphQL API
Vehicle Registration API built in Laravel, Lighthouse and GraphQL

## Features
- User Login
- Register new User
- Logout
- Register a Vehicle
- Update Vehicle details
- List all Vehicles belonging to a specific user

## Setup
Clone this repository to your machine and run `composer install` to install all the dependencies.

Copy `.env.example` and rename it as `.env`

Set all the environment variables as needed.

Then run `php artisan migrate`

run `php artisan db:seed` to populate your database with data.

run `php artisan serve` to start server

The API is now running. Copy the url link provided and paste it on your favourite
browser. It should look like `http://127.0.0.1:8000` this will display laravel default landing page.

To navigate to GraphQL Playground got to `http://127.0.0.1:8000/graphql-playground`

## Testing
This API contains test cases for all the features named above. Run `php artisan test` to run tests.

### Technologies
- Laravel
- Lighthouse
- GraphQL
- JWT

