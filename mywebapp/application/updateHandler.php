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

    $uploadedImage = uploadImage();

    $params = [
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':email' => $email,
        ':address' => $address,
        ':city' => $city,
        ':postal_code' => $postcode,
        ':telephone' => $telephone,
        ':id' => $postedData['id']
    ];

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
}

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

unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// call to the update function
update();