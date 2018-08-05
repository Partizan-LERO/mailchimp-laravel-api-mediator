

## How to install

1. `git clone https://github.com/Partizan-LERO/softex.git`.
2. `composer install`.
3. `php artisan key:generate`.
4. `php artisan migraet`.
5. `php artisan jwt:secret`.
6. Create Mailchimp account and generate apikey.
7. Register user by using API request: `POST http://softex.loc/api/register`. Request body must contains 
your `name`, `email`, `password`, `mailchimp_api_key`. Save access_token and use it for making requests.

For getting all routes, run this artisan command: `php artisan route:list`.

Notice that routing response cache(for GET routes) clear normally after CUD-operations. 
But for clearing cache you can use artisan command `responsecache:clear` as well.

That's all! 