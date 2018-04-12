## PHP & MySQL Lab - 13-04-2018

#### 7. Implement Login functionality

Now that we have the registered the user, let's implement the login functionality.

__NOTE__: I will skip the changes that we must do in `login.html`, I think you can figure that out.

You should also rename `login.html` to `login.php`.

i. Let's create the PHP script that will handle the login request. Create a file `loginHandler.php` in `application/`

```php
  session_start();

require_once ("database/DatabaseConnection.php");

unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

function login() {
    $postedData = $_POST['data'];

    $email = $postedData['email'];
    $password = $postedData['password'];

    // create PDO connection object
    $dbConn = new DatabaseConnection();
    $pdo = $dbConn->getConnection();

    // retrieve user with the email
    try {
        $statement = $pdo->prepare("SELECT * FROM `users` WHERE email = :email LIMIT 1");
        $statement->bindParam(':email', $email);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $userData = $result[0];

        // no user matching the email
        if (empty($result)) {
            $_SESSION['error_message'] = 'Invalid email / password!';
            header('Location: /mywebapp/login.php');
            return;
        }

        $userEncryptedPassword = $userData['password'];

        // verify the incoming password with encrypted password
        if (password_verify($password, $userEncryptedPassword)) {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['userID'] = $userData['id'];
            $_SESSION['success_message'] = 'User successfully';
            header('Location: /mywebapp/login.php');

            unset($_SESSION['error_message']);
            return;
        }
    } catch (PDOException $exception) {
        var_dump($exception->getMessage());
    }

    $_SESSION['error_message'] = 'Invalid email / password!';
    header('Location: /mywebapp/login.php');
}

login();
```
