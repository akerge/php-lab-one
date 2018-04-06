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

__NOTE__: The application we are working on is the same **mywebapp** (located in this repository) from last lab.

#### 1. Create database

```mysql
  CREATE DATABASE IF NOT EXISTS `icd0007_app_db`;
```

#### 2. Create a table in the database
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

#### 3. Add new column
```mysql
  ALTER TABLE `users` ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `is_admin`;
```

#### 4. Rename phone column
```mysql
  ALTER TABLE `users` CHANGE `phone` `telephone` VARCHAR(20) DEFAULT NULL;
  
  -- Also, we want to make sure email and phone number is unique
  ALTER TABLE `users` ADD UNIQUE( `email`, `telephone`);
```

Now, let's head to our PHP app.

Going forward, we need to have a better code organization to avoid having scattered code all around.

Inside your project folder, create this folder structure `application/database`

#### 5. Create a connection class

Create the connection class file `DatabaseConnection.php` in the folder `application/database`.

```php
  <?php

  /**
   * Class DatabaseConnection
   *
   * This class establish connection to the MySQL database
   */
  class DatabaseConnection
  {
      public function getConnection()
      {
        // check you environment setup and update the info below, if needed.
        $host = '127.0.0.1';
        $port = '8889';
        $user = 'root';
        $password = 'root';
        $database = 'icd0007_app_db';

        // optional
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // set the PDO error mode to exception
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Specifies that the fetch method shall return each row as an array
        ];

        // We are putting the connection code inside a try and catch block
        // This will allow us to handle any problem that may occur
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $password, $opt);

            return $pdo;
        } catch (PDOException $exception) {
            print_r($exception->getMessage());
        }

        // if the code execution reached here, it means an error has occurred, so we will return null
        // to indicate to the caller of this method that the pdo object is null/empty
        return null;
    }
  }
```

In the code above, we are using the [PHP Data Objects (PDO) extension](http://php.net/manual/en/intro.pdo.php) - which defines a lightweight, consistent interface for accessing databases in PHP.

#### 6. Insert registration data into the `users` table

First, let's move the `registerHandler.php` into the `application` folder.

Inside the `registerHandler.php`, we will make use of the `connection class` above, by including `DatabaseConnection.php`.

__NOTE__: Every PHP file that requires us to interact with the database, we will need to include the connection class.

i. At the top of `registerHandler.php` after `<?php`, put the code below

```php
    require_once ("database/DatabaseConnection.php");
```

ii. We will end up writing more codes in the `registerHandler.php` file, so it's better to organize similar logics and wrap them in a function, so by now the `registerHandler.php` should look like the code below.

```php
      require_once ("database/DatabaseConnection.php");
      
     /**
     * This is the function that handles the registration
     */
    function register() {
        $postedData = $_POST['data'];

        $email = $postedData['email'];
        $firstname = $postedData['firstname'];
        $lastname = $postedData['lastname'];
        $password = $postedData['password'];
        $confirmPassword = $postedData['confirm_password'];
        $address = $postedData['address'];
        $city = $postedData['city'];
        $postcode = $postedData['postcode'];
        $telephone = $postedData['telephone'];

        // TODO: we should validate our data before inserting to database

        // create PDO connection object
        $dbConn = new DatabaseConnection();
        $pdo = $dbConn->getConnection();
    }

    // call to the register function
    register();
```

iii. Next we will write the insert statement and use the `$pdo` object to execute the query.

Put the code below after the `$pdo = $dbConn->getConnection();` in `registerHandler.php`

```php
    // insert using PDO prepared statement, it helps prevents against sql injection attack (more on that later)
    $params = [
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':password' => password_hash($password, PASSWORD_DEFAULT), // we MUST not store password as plain text
        ':email' => $email,
        ':address' => $address,
        ':city' => $city,
        ':postal_code' => $postcode,
        ':telephone' => $telephone,
    ];

    try {
        $statement = $pdo->prepare(
            "INSERT INTO `users` (`firstname`, `lastname`, `password`, `email`, `address`, `city`, `postal_code`, `telephone`) 
                          VALUES (:firstname, :lastname, :password, :email, :address, :city, :postal_code, :telephone)"
        );

        $statement->execute($params);

        if ($pdo->lastInsertId()) {
            return "Registration successful";
        }

    } catch (PDOException $e) {
        // usually this error is logged in application log and we should return an error message that's meaninful to user 
        return $e->getMessage();
    }

    return "Registration was not successful";
```

iv. Go to `main.js` file

* Replace the `url` to point to `application/registerHandler.php`, since we change the project structure

* Fill the registration form, submit and check the database table `users` to verify data is inserted.
