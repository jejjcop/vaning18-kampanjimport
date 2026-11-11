### vaning18-kampanjimport
A service that imports campaigns from an external REST API into MySQL
It uses Bootstrap 

### Start the server

Go to the project folder

First run the migrations:
php database/migrate.php

Then start the webserver
php -S localhost:8000

### PHPUnit
php phpunit-13.4.6.phar

### Users

Two test user accounts have been created:
Username: user1
Password: password

Username: user2
Password: password

### Database
This project utilizes SQLite since its just a simple test project

Make sure the following exists in your php.ini-file:
extension=pdo_sqlite

The app uses Write-Ahead Logging (WAL) to improve performance for when the database is being accessed concurrently

### Duplicates

A note on checking for duplicates when importing campaigns:
The app assumes that the ID associated with each campaign from the API is unique to this campaign even if other campaign data changes
A check is therefore made to check if the ID already exists in the database, if it does the app presumes that the campaign has already been imported. 
If a campaign already exists in the database (has already been imported), the existing campaign is updated

### Prevention of corrupt data
To prevent data from being corrupted if many imports are run at the same time or if one fails, the app uses TRANSACTIONS to write to the DB when inserting new campaigns

