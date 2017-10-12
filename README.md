# SPORTS-EVENT

Sport event is basic racing event where athlets complete the races, they crosses through corridor
and finish line. Both point have chip sensors which listens to chip devices attached to athlets hands 
once recived data, device send information to Port. 

The Front end is developed in Angular 4 can be find [here](https://github.com/junahmed46/SPORTS-EVENT-FRONT)

## Setup Guidlines 

Clone The Current Repo

Make .env file `cp .env.example .env`

Configured Database varaibles

Assigned values to .env file *(you can use any APP_KEY)*
```
APP_KEY=oNaRfe6Jg9UmYRyrojw/lpoMK39bHgzk4EGDyK7S+HM=
CACHE_DRIVER=array
SESSION_DRIVER=cookie
QUEUE_DRIVER=database
```

Run composer update `composer update`

To setup proper tables run `php artisan migrate`

There are some data requires in athlets table by default 100 athlets will be added via `php artisan db:seed` 

**DB seed is important**

IF there are permission need to be steup 

```
find  -type f -exec chmod 644 {} \;
find  -type d -exec chmod 755 {} \; 
```

## Database Information

https://github.com/junahmed46/SPORTS-EVENT/blob/master/Database.md

## APIs information 

Provided APIs details are mentioned in below URLs

https://github.com/junahmed46/SPORTS-EVENT/blob/master/apiary.apib

or 

http://docs.sporteventmooncasecade.apiary.io/#


## Some Code explanation


