ToDoList : the solution to stay organised  !
========

What is it ?
----
I am student at Openclassrooms and the 8th project consists of improving an existing application.  
You must be logged in to add a task, mark it as done, edit it and delete it.  
You cannot delete the tasks of the other users, but if you have the admin role, you can delete the ancient tasks created by an anonymous user.


What it contains ?
----

You can find in this repository a Symfony 6.0 structure, with a great application to manage your tasks, this readme, a document to know how to contribute to this project and many unit and functionnal tests to test this application.

What do I need ? (Prerequisite)
----
You must have installed at least :
- PHP 8.1.10 (with xdebug and opcache extensions enabled)
- Symfony 6.0
- Composer

How to install the project
----
1. Clone or download this repository, create a new folder "ToDoList" and place the projet in it.

2. In the folder "ToDoList", run `composer install` in command.

3. Create a new database : change the value of DATABASE_URL in the file .env to match with your database parameters.

4. Run `symfony console doctrine:database:create` in command to create your database.

5. Run `symfony console doctrine:schema:update` in command to apply the right schema in your database.

6. Run `symfony console doctrine:migrations:migrate` to apply all migrations files.

7. Generate user and tasks fixtures with the command `symfony console doctrine:fixtures:load`.
   
8. Repeat points 3 to 7 for the test environment (the file to modify is .env.test and add the option `--env=test` to the commands).


Code Climate analysis
----
[![Maintainability](https://api.codeclimate.com/v1/badges/6524c4921ff71c39db88/maintainability)](https://codeclimate.com/github/FloryssRu/ToDoList/maintainability)



Origin of this project
----

Project Basis #8: Enhance an existing project

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1