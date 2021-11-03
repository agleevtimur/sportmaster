### Start up
```
docker-compose up --build
//inside php-fpm container
php bin/console doctrine:migrations:migrate
```
---
### Web API  
[link for postman collection](https://www.getpostman.com/collections/6f6a2bcbf3a49eb69fef)  
or use curl requests in ''/requests'' folder

---
### Testing
```
vendor/bin/phpunit
```
