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
## Generating Documentation
```bash
docker pull phpdoc/phpdoc
docker run --rm -v $(pwd):/data phpdoc/phpdoc run -d ./app -t ./output_doc
sudo chown -R $USER:$USER ./output_doc # to fix user permission from docker
```

