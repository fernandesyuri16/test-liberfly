# ⛱ **Holiday Plans**

This project consists of a RESTful API for managing vacation plans and these are some features:

1. User registration, login and logout;
2. Listing, registering, editing and deleting vacation plans;
3. Issuing a PDF report with details of a particular vacation plan;
7. Authentication for handling vacation plans and users;

The folder in the directory contains the project developed in Laravel.



## 💻 **Project setup**



### 🛫 Initialization

To get started, we first need to initialize the Docker containers by running the following commands in the project root:
```
sudo make run
```
After the containers have been started, you can access the Adminer database manager on url: "localhost:8080".

With the initialization of the project, we already have the popularization of the bank through Factories and Seeders.

All set! Requests can now be made using the routes defined as an example in the documentation.



### 📚 API Documentation

* [Holiday Plans - Docs](http://localhost:8001/api/documentation)
* Remember that to access the documentation you need to upload the project containers!


### 📝 Unit Tests

To carry out the tests defined for each endpoint, simply execute the following command in the root of the project:
```
  docker compose exec php php artisan test
```
this way, the command will be executed inside the docker, in the php container, and finally doing the `php artisan test`


## 🔧 Finalization

To finish the project completely, it is necessary to finish all the containers that have been started.

To finalize the API containers, run the command in the project root:

```
sudo make stop
```

Note: if you get an error on the first start, end the application and start it again.

## 🏗️ **Built with**

* [PHP](https://www.php.net/)
* [Laravel](https://laravel.com/)
* [Docker](https://www.docker.com/)

---
Developed by [Yuri Fernandes](https://github.com/fernandesyuri16)

