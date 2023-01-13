<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## О Laravel

В тестовом проекте используется laravel version v.9

## установка Laravel

1)Развернуть проект в docker согласно конфигурации docker-compose.yml

```
Поднять контейнеры командой docker-compose up -d
Войти внутрь контейнера командой docker exec -it kinoboom_app bash
```

2)Установка через Composer

```
Внутри контейнера composer create-project laravel/laravel kinoboom
```

3)Установка компонентов laravel для поиска Scout

```
Внутри контейнера composer require laravel/scout
```

4)Установка драйвера поиска Algolia PHP SDK

```
Внутри контейнера composer require algolia/algoliasearch-client-php
```

## Настройка соединения с базой данных

Редактировать файл `/.env`:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:PvB6aC68HuMjgrXZaZZn/MiDfux4ZZ9OGqQ9A6FgGuM=
APP_DEBUG=true
APP_URL=http://localhost::8876

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=kinoboom_base
DB_USERNAME=root
DB_PASSWORD=root

```

## Настройка поиска

1)Публикация файла конфигурации Scout в ваш каталог config

```
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```
