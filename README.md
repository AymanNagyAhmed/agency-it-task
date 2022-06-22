## About Agency IT Task

Agency IT Task is a web application that provide Administrators the ability to review the performance of the employees as well as give the employees the opportunity to give feedback on the administration reviews.

## Prerequisites
- Working Docker installation 
refer to [this guide](https://docs.docker.com/engine/install/) for more info
- docker-compose
## Setup 
### Using Sail
- starting the server
```bash
./vendor/bin/sail up -d
```
- migrate database  tables
```bash
./vendor/bin/sail php artisan migrate
```
- seed database tables with dummy
```bash
./vendor/bin/sail php artisan db:seed --class=DatabaseSeeder
```

