## PHP & MySQL Lab - 06-04-2018

In continuation of our last lab, we will be looking into how we can connect our PHP application to a MySQL database.

During the lecture today, you were introduced to MySQL, basic SQL Syntax and operations that you can perform on the databse.

In today's lab, we will try and cover the following:

* Create a database using SQL

* Create a table inside the database to store user registration data

* Create connection from the PHP application to MYSQL database

* Insert submitted user registration data into the database table

* Update the login form to link to login logic in the PHP

* Implement login logic in the PHP code

#### Create database

```mysql
  CREATE DATABASE IF NOT EXISTS `icd0007_app_db`;
```

#### Create a table in the database
```mysql
  CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text,
  `city` varchar(64) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postal_code` varchar(15) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `is_admin` tinyint(1) unsigned DEFAULT '0' COMMENT 'A flag to indicate if a user is an admin (1 - admin, 0 - regular user)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;
```

#### Add new column
```mysql
  ALTER TABLE `users` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `is_admin`;
```

#### Rename phone column
```mysql
  ALTER TABLE `users` CHANGE `phone` `telephone` VARCHAR(20) DEFAULT NULL;
```

