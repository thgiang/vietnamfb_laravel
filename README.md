## Setup

- Copy & paste `.env.example`, change the clone file name to `.env`
- Open `.env`, correct `DB_HOST`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` for your environment
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan serve`, default ip:host is `127.0.0.1:8000`

##setup Postman to call api
- add header params: `isDev=1`, `HTTPORIGIN=http://vietnamfb.local:8081`
