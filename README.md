# Docker Container with REST API using Laravel Lumen 5.8
This is a docker container based REST API created using latest Laravel Lumen framework.

## REST API Details
1. Login/Logout functionality to authenticate user
2. User registration, with the below fields;
	a. First Name
	b. Last Name
	c. Email
	d. Mobile number
	e. Gender
	f. Birthday
3. Todo item details
	a. Name
	b. Description
	c. Date time
	d. Status
	e. Category
4. After a successful login, we should be able to
	a. Show user specific todos list (ability to filter per day, month or show all)
	b. Display task list by categories and/or by status (Completed, Snoozed, Overdue)
	c. Task and Category management

## Installation
I use [docker](https://docs.docker.com/install/) and [composer](https://getcomposer.org/download/) for installation. You need to install those separately by yourself.

```
> git clone https://github.com/abidaks/lumen-laravel-rest-api
> cd lumen-laravel-rest-api
> docker-compose build && docker-compose up -d
> composer create-project laravel/lumen src
> chmod -R 777 src
```

By now you have created a docker container with all the necessary installations to run this project.
You can check this http://localhost:8080/ url to confirm.


Now copy all the files from lumen-files folder to specified folders.
After copying files you need to run migration to make it work.

```
> docker-compose exec php php /var/www/html/artisan migrate
```

I also uploaded the postman script under postman-urls folder which contains all the urls's.


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)