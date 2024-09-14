Database

1. use MySQL workbench and import organization_chart.sql which can be located inside the project.

To install 

1. git clone ___

2. cd __

3. composer install

4. npm install

Setup

1. cp .env.example .env

2. php artisan key:generate

3. Update the `.env` file with your database credentials. For example:

 DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=org
    DB_USERNAME=root
    DB_PASSWORD=

4. php artisan migrate --seed

Usage

1. php artisan serve 





