**Code structure**
-------------------
```
/app - project code

/app/src/Controller/ArticleController - base app controller
/app/src/Entity/Artice - base app model (dto)
/app/src/Entity/Article - base app repository
/app/templates - views of app
/app/config - app configuration
/app/var - tmp folder

/docker - files for docker start
docker-compose.yml - docker-compose instruction
```

**Install**
-------------
Start
```
docker-compose build
docker-compose up -d
docker exec -ti {php-fpm-container-id} /bin/bash
composer install 
```
Doctrine init
```
php bin/console cac:cle
php bin/console doctrine:migrations:migrate
```

After that site available on http://0.0.0.0:8080/