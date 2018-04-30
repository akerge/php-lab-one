
## PHP & MySQL Lab - 20-04-2018

In previous labs, we have covered `user registration`, `login` and `logout`. In today's lab we will cover the following:

* [Recap](https://github.com/ICD0007/php-lab-one/blob/master/php-mysql-lab-13-04-2018.md)

* Retrieve logged in user data

* Update user data

* Upload user picture (File upload)

### 1. Retrieve logged in user data

Now that the user has logged in, let's update the `profile.php` by creating an update form and display user data.

i. Inside your `profile.php` replace the section below

```html
    <div class="content">
      <br/>
      <div class="content-area">

          <h2>Profile</h2>
          <?php
              echo 'Now I can access the profile page<br>';

              echo 'User ID stored in session is - ' . $_SESSION['userID'];
          ?>
      </div>
    </div>
```

with

```html
    <div class="content">
            <br/>
            <div class="content-area">
                <h2>Update profile</h2>
                <br/>
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<p>' . $_SESSION['error_message'] . '</p>';
                } elseif (isset($_SESSION['success_message'])) {
                    echo '<p>' . $_SESSION['success_message'] . '</p>';
                }
                ?>
                <form id="updateForm" action="" method="POST">
                    <p>
                        <label>Email: </label>
                        <input type="text" name="data[email]"/>
                    <p>
                    <p>
                        <label>First Name: </label>
                        <input type="text" name="data[firstname]"/>
                    <p>
                    <p>
                        <label>Last Name: </label>
                        <input type="text" name="data[lastname]"/>
                    <p>

                    <p>
                        <label>Address: </label>
                        <textarea name="data[address]"></textarea>
                    <p>
                    <p>
                        <label>City: </label>
                        <input type="text" name="data[city]"/>
                    <p>
                    <p>
                        <label>Postcode: </label>
                        <input type="text" name="data[postcode]"/>
                    <p>
                    <p>
                        <label>Telephone: </label>
                        <input type="text" name="data[telephone]"/>
                    <p>
                    <p>
                        <input type="submit" name="btnSubmit" value="Update profile" class="button marL10"/>
                    <p>
                </form>
            </div>
        </div>
```

ii. In order to display the data of the currently logged in user, we need to get the data from the database.

To do that we need to create `User class` which will serve as class that will interact with the database via the `DatabaseConnection` class.

So, create a new folder `models` inside our project `mywebapp/application/models`. Inside `models` folder create a new file `User.php`.

```php
    <?php

      session_start();

      require_once (__DIR__ . '/../database/DatabaseConnection.php');

      /**
       * Class User
       * @property int $id
       * @property string $firstname
       * @property string $lastname
       * @property string $email
       * @property string $address
       * @property string $city
       * @property string $country
       * @property string $postal_code
       * @property string $telephone
       */
      class User {

          public function getUserProfile()
          {
              // create PDO connection object
              $dbConn = new DatabaseConnection();
              $pdo = $dbConn->getConnection();

              // get user id from session variable
              $userID = $_SESSION['userID'];

              $statement = $pdo->prepare("SELECT id, firstname, lastname, email, address, city, country, postal_code, telephone FROM `users` WHERE id = :id LIMIT 1");
              $statement->bindParam(':id', $userID);
              $statement->execute();

              $result = $statement->fetchAll(PDO::FETCH_ASSOC);

              // no user matching the id
              if (empty($result)) {
                  return [];
              }

              $userData = $result[0];

              return $userData;
          }
      }

      $user = new User();
      $userData = $user->getUserProfile();
```

iii. To have access to the `User` class in `profile.php`, we must include the class file in `profile.php`

Replace the `php` code at the beginning of `profile.php` with the one below.

Notice we just added `require_once ('application/models/User.php');` The comment `@var ... ` is optional (just for IDE support).

```php
    <?php
      require_once ('protected_access_check.php');
      require_once ('application/models/User.php');

      /**
       * @var $userData User
       */
   ?>
```

iv. Now that we have retrieved the user data, we need to display it in the `updateForm`.

Replace the profile update form with the content below.

```html
    <form id="updateForm" action="" method="POST">
      <input type="hidden" name="data[id]" value="<?= $userData['id']; ?>"/>
      <p>
          <label>Email: </label>
          <input type="text" name="data[email]" value="<?= $userData['email']; ?>"/>
      <p>
      <p>
          <label>First Name: </label>
          <input type="text" name="data[firstname]" value="<?= $userData['firstname']; ?>"/>
      <p>
      <p>
          <label>Last Name: </label>
          <input type="text" name="data[lastname]" value="<?= $userData['lastname']; ?>"/>
      <p>
      <p>
          <label>Address: </label>
          <textarea name="data[address]"><?= $userData['address']; ?></textarea>
      <p>
      <p>
          <label>City: </label>
          <input type="text" name="data[city]" value="<?= $userData['city']; ?>"/>
      <p>
      <p>
          <label>Postcode: </label>
          <input type="text" name="data[postcode]" value="<?= $userData['postal_code']; ?>"/>
      <p>
      <p>
          <label>Telephone: </label>
          <input type="text" name="data[telephone]" value="<?= $userData['telephone']; ?>"/>
      <p>
      <p>
          <input type="submit" name="btnSubmit" value="Update profile" class="button marL10"/>
      <p>
  </form>
```

What we have done here is, we have populated the `value attribute` of each form field with the data retrieved from the database.

For example for email field we added `value="<?= $userData['email']; ?>"` in the input field. This will display the email in the email field.

We did the same for the rest of the fields except for the textarea `<textarea name="data[address]"><?= $userData['address']; ?></textarea>` which doesn't have value attribute, but we put the `address` data between textarea tag.

v. Now, you can login with an existing user credentials to see the changes.

You should see the profile page with populated user data.

![Alt text](./profile_update_page.png)

### 2. Update user data

i. Create `updateHandler.php` in `mywebapp\application` and put the content below

```php
      <?php
          session_start();
          require_once ("database/DatabaseConnection.php");

          /**
           * This is the function that handles the registration
           */
          function update() {
              $postedData = $_POST['data'];

              $email = $postedData['email'];
              $firstname = $postedData['firstname'];
              $lastname = $postedData['lastname'];
              $address = $postedData['address'];
              $city = $postedData['city'];
              $postcode = $postedData['postcode'];
              $telephone = $postedData['telephone'];

              // create PDO connection object
              $dbConn = new DatabaseConnection();
              $pdo = $dbConn->getConnection();

              $params = [
                  ':firstname' => $firstname,
                  ':lastname' => $lastname,
                  ':email' => $email,
                  ':address' => $address,
                  ':city' => $city,
                  ':postal_code' => $postcode,
                  ':telephone' => $telephone,
                  ':id' => $postedData['id'],
              ];

              try {
                  $statement = $pdo->prepare(
                      "UPDATE `users` SET firstname = :firstname, lastname = :lastname, email = :email,
                                  address = :address, city = :city, postal_code = :postal_code, telephone = :telephone
                                  WHERE id = :id"
                  );

                  $statement->execute($params);

                  $_SESSION['success_message'] = 'Update was successful';
                  header('Location: /mywebapp/profile.php');
                  return;
              } catch (PDOException $e) {
                  var_dump($e->getMessage());
                  die();
              }
          }

          unset($_SESSION['success_message']);
          unset($_SESSION['error_message']);
          // call to the update function
          update();
```

ii. Added the `form action` to the update form as `action="application/updateHandler.php"`

### 3. Upload user picture

In PHP, it's also possible to upload a file. To demonstrate this, we need to add new field to the form.

i. Add new field to update form

```html
      <p>
          <label>Profile Picture: </label>
          <input type="file" name="fileToUpload" id="fileToUpload">
      <p>
```

ii. Add form enctype

Add `enctype="multipart/form-data"` to the form tag to look like this

`<form id="updateForm" action="application/updateHandler.php" method="POST" `enctype="multipart/form-data"`>`

It specifies which `content-type` to use when submitting the form, if we don't specify `enctype`, file upload wouldn't work.


iii. We need to write the php code to handle the image upload

Inside the `updateHandler.php`, add a new function below

```php
    
/**
 * @return string
 */
function uploadImage()
{
    // no file selected
    if(empty($_FILES["fileToUpload"]['name'])) {
        return '';
    }

    $target_dir = __DIR__ . "/../uploads/";
    $target_file = basename($_FILES["fileToUpload"]["name"]);

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check === false) {
        $_SESSION['error_message'] = "Invalid image file.";
        return false;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $_SESSION['error_message'] = "Sorry, your file is too large.";
        return false;
    }

    $allowedFileType = [
        "jpg",
        "png",
        "jpeg",
        "gif",
    ];

    $imageFileType = strtolower(pathinfo($target_dir . $target_file, PATHINFO_EXTENSION));

    // Allow certain file formats
    if(!in_array($imageFileType, $allowedFileType)) {
        $_SESSION['error_message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        return false;
    }

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $target_file)) {
         return $target_file;
    } else {
        $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
        return false;
    }
}
```

iv. Now that the image is upload, we need to store the image path to a column in `users` table, so let's create the new column

```mysql
    ALTER TABLE `users` ADD `profile_avatar` VARCHAR(255) DEFAULT NULL AFTER `created_at`;
```

v. Modify function `update()` to save uploaded image path to `users` table.


```php
    $uploadedImage = uploadImage();
    
    if ($uploadedImage === false) {
            $params[':profile_avatar'] = null;
        } else {
            $params[':profile_avatar'] = $uploadedImage;
        }
    
        try {
            $statement = $pdo->prepare(
                "UPDATE `users` SET firstname = :firstname, lastname = :lastname, email = :email,
                            address = :address, city = :city, postal_code = :postal_code, telephone = :telephone, 
                            profile_avatar = :profile_avatar
                            WHERE id = :id"
            );
    
            $statement->execute($params);
    
            $_SESSION['success_message'] = 'Update was successful';
            header('Location: /mywebapp/profile.php');
            return;
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            die();
        }
```

### Debug

Check this file for errors - `MAMP/logs/php_error.log`

[Previous Lab](https://github.com/ICD0007/php-lab-one/blob/master/php-mysql-lab-13-04-2018.md) | [Next Lab](https://github.com/ICD0007/php-lab-one/blob/master/php-lab-27-04-2017.md)
