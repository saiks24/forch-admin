**Install**
-------------
```docker-compose up -d
docker exec -t {php-fpm-container-id} /bin/bash
composer install 
php bin/console cac:cle
php bin/console doctrine:migrations:migrate
```

After that site available on http://0.0.0.0:8080/