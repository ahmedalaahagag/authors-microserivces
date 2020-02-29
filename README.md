# Lumen Simple Microservice (Authors CURD Api) 

This project was done as a part of 3 **Microservices** project using **Lumen** and **Laradock** 
Can be used as a stand a lone project and part of the **Microservices** project

>Lumen is designed for building lightning fast micro-services and APIs

**Laradock** is a full PHP development environment for Docker that Includes prepackaged Docker Images, all preconfigured to provide a wonderful PHP development environment. Laradock is well known in the Laravel/lumen community, as the project started with single focus on running Laravel projects on Docker.

## Usefull Links
- [Laradock](https://laradock.io/)
- [Lumen](https://lumen.laravel.com/)

## Requirements

To be able to run this project one needs the following technologies:

- [Docker](https://www.docker.com/) -> Laradock uses Docker
- [Composer](https://getcomposer.org/) -> No need if one uses Laradock/Docker

if one want to start Lumen from scratch Composer is need as a package manager.

## Instructions
Following the instructions one should be able to run this project or at least have a good base how to start a Lumen project using Laradock.

1. `git clone git@github.com:ahmedalaahagag/authors-microserivces.git`
2. Rename `.env.example` file to `.env`. The
.env file is the environment file that deals with project configurations like database credentials, api keys, debug mode, application keys etc and this file is out of version control.
3. Set your application key to a random string. Typically, this string should be 32 characters long. In .env file it is called eg APP_KEY=akkfjvlakengoemvgkcgelapchyekci the same goes to
ACCEPTED_SECRETS=akkfjvlakengoemvgkcgelapchyekci which is a key that should be sent with every API call to this project.
4. Laradock clone it inside the project folder `git clone https://github.com/laradock/laradock.git`

## To run this project as a standalone project 
5. `cd laradock`
6. `cp env-example .env`
7. `docker-compose up -d nginx mysq` => To start the server
8. `docker-compose exec workspace bash` => to get access to virtual machine and here one can execute any artisan command
9. Run `composer install` => to install all php dependencies. This will create a vendor folder which is the core lumen framework
10.  Inside `.env` file in the project root update `DB_HOST=mysql`
11. With a SQL tool as `Sequel Pro` or similar connect to the MySQL to create a new DB. Or use the Docker MySQL workspace bash to use commands instead.
12. Default values `username:root password:root host:mysql`
13. Update database name and else in `.env`

Remember all the the **Docker** commands have to be run it under **Laradock** folder as there the Docker files are placed.

If one wants to run this project as it is after `composer install` run migration as `php artisan migrate` to update the DB with the right tables. Then seed with `php artisan db:seed` to populate the DB with some fake data.

## Troubleshoot some possible issues
It is possible one has issues with connecting to MySQL image of Docker. A possible solution as follow:

From terminal
```
$ docker-compose exec mysql bash
$ mysql -u root -p

# Execute commands
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'root';
ALTER USER 'default'@'%' IDENTIFIED WITH mysql_native_password BY 'secret';
```

May be need to restart the container after the changes
```
$ docker-compose down
$ docker-compose up -d nginx mysql
```

## Tutorial
How to create a **Simple Blog API**. Step by step explanations to get start with **Lumen**

**1) Create a Lumen project**

First one need to install Lumen via Composer:
```
composer global require "laravel/lumen-installer"
```

Then can run:

```
lumen new Authors
```

**2) Clone Laradock inside the Authors folder project**

The steps were showed above what to do with Laradock and Docker parts.

**3) Connect to the MySQL container**
One can connect via a program like Sequel Pro or Navicat or else to the MySQL container. Then need to create the DB.

Example of a connection set up:
![connection_db](doc/Edit_Connection_Laradock__MySQL.png)

Remember the name of the DB need to be put inside the `.env` file along with the credetials of it.

```
DB_CONNECTION=mysql
DB_HOST=mysql -> This need to be in this way and if any connection issues please refer to the troubleshoot section above
DB_PORT=3306  -> Change this if you use a different port
DB_DATABASE= < DB_NAME > < ex. authros >
DB_USERNAME= < DB_USERNAME > < ex. root >
DB_PASSWORD= < DB_PASSWORD > < ex. root >
```

After one done the first preliminary set up steps, then is the time to move forward creating the API itself.

**4) Eloquent**

In simple words allows calling built-in functions instead of writing complex queries. The Eloquent ORM included with **Laravel/Lumen** provides a beautiful, simple **ActiveRecord** implementation for working with the database. Each database table has a corresponding **Model** which is used to interact with that table. Models allow you to query for data in your tables, as well as insert new records into the table. For example, one can say `Post::all()` to get all the blog posts inside posts table rather than writing `select * from posts`. Where Post in `Post::all() is a `model`.

Then to use **Eloquent** one will uncomment the `$app->withEloquent()`
in your `bootstrap/app.php`

**5) Facades**

A **facade** class is a wrapper around a few classes serving a particular purpose to make a series of steps required to perform a task in a single function call.

Then i will uncomment the `$app->withFacades()` call inside `bootstrap/app.php` file to use **Laravel Facade**.

**6) Authors**
Then inside the **app** folder, will create `Author.php`. It is called a model in **MVC framework**. 
It will reflect **authors table** inside database which has not been created yet Inside this model will have set some fillable `fields =>name` and `gender` and `country` 
as all **Eloquent models** protect against mass-assignment by default. A **mass-assignment** vulnerability occurs when a user passes an unexpected HTTP parameter through a request, and that parameter changes a column in your database you did not expect

See more at [mass-assignment](https://laravel.com/docs/5.7/eloquent#mass-assignment)

**7)Create a migration**

To create a migration one need to be inside the **Docker container workspace**:
```
docker-compose exec workspace bash
```

Then:

```
root@688df818e9b7:/var/www# php artisan make:migration create_authors_table
```

This will create migration file inside `database/migrations`

Example: `2020_02_27_153519_create_authors_table.php`

A migration file usually defines the schema of the database table.

See more at [migrations](https://laravel.com/docs/5.7/migrations)

Then run command
```
php artisan migrate
```
This will migrate schema to database according to what is present in migration file. Now your database will have **authors table**.
This is how Eloquent makes it so easy to create tables, share this schema with the team and use its simple functions to generate complex sql queries.

**8) Fake data to use for the test of the API**

Now the issue how we test the API if we do not have any data to
test actually.

**Lumen** has a very fine way to create dummy data. It is called **Model Factories**. That uses [Faker](https://github.com/fzaninotto/Faker) package behind the scenes. Let's dive into.

Inside `database/factories/ModelFactory.php` will define a factory for each table (1 only for posts table in this case). A factory is a suitable word because a factory creates object based on rules defined inside the factory.

Now we need the a **seeder class** to call this factory to start creating objects and tell it a number to produce as well. So command `php artisan make:seeder AuthorsTableSeeder.

Alternatively you can create a factory as in **database/factories/ModelFactory.php** to create objects of Authors  
Will ask it to create 50 objects whenever it is called. Inside
`database/seeds/DatabaseSeeder.php` call `AuthorsFactory`.
Now we will run `php artisan db:seed` command to seed the database. Which will call `run()` in `DatabaseSeeder.php` and seed all listed seeders. 
We now have 50 dummy records inside authors table.

Example:
![seeds](doc/Screenshot 2019-04-03 at 23.57.20.png)

**9) API end points**

If we go to `routes/web.php` here is we define our endpoints/routes. For example if one wants to get all posts one will set an endpoint with `url posts/all`.
See the file. One used `Authors::all()` (in a callback function)
which is **Eloquent** way to fetch all the results for a
model which has also been discussed earlier.

Now if one hit the endpoint through **Postman** 
Attached is a collection that can be imported to **Postman** 
`Authors.postman_collection.json`
or visit in browser`localhost/authors` one should see 50 authors posts in json.

# What to expect with the code
- Standardized response format `ApiResponder.php`
- `Secret Key` protected endpoints `AuthenticateAccess.php`
- Standardized exception response format `Handler.php`
- REST Based API format `web.php`