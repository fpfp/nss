# NSS Tshirt Designer

> ### Simple app to design tshirt


----------

# Getting started

## Installation


Clone the repository

    git clone git@github.com:fpfp/nss.git

Switch to the repo folder

    cd nss

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

    - set DB_* with your database info
    - set APP_URL your website url
    - set WATERMARK_IMAGE with path to your watermark png
    - set GRAPHICS_PATH with path to your svg graphics to use inside editor

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Link storage folder to public

    php artisan storage:link    

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

## Database seeding

**Populate the database with seed data.**

Run the database seeder and you're done

    php artisan db:seed

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh


----------

# Code overview

# Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api

Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|
| Yes      	| X-Requested-With 	| XMLHttpRequest   	|
| Optional 	| Authorization    	| Token {Sanctum}      	|


----------
 

# Testing Frontend

Install all the dependencies using npm

    npm install

Run the Vue frontend

    npm run prod

The app can now be accessed at

    http://localhost:8000




----------
 