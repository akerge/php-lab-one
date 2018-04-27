<?php
session_start();

require_once("database/DatabaseConnection.php");

require_once("mailSender.php");

class ResetPassword
{
    const RESET_HASH = 'jkewowefserewtweirwrwfmf';

//     This approach of the hardcoded reset above is just for demo we should generate a token for each password request
//     and stored it in the database table e.g. password_reset_tokens, with field user email and generated token
//     so when the user click the reset link we use it to double check if that request to reset password is valid
//
//        1. We need this token to check later, when user click the reset link whether the reset request is valid
//
//        2. We should also check if the token-email pair is valid,
//           to achieve this we will need to store an auto-generated token with the email
//
//        3. We can also set expiry time for each token, so that if the user clicks the reset link after 24hours, then the link is no long valid

    function resetPasswordRequest() {
        $postedData = $_POST['data'];
        $email = $postedData['email'];

        $dbConn = new DatabaseConnection();
        $pdo = $dbConn->getConnection();

        try {
            $statement = $pdo->prepare("SELECT * FROM `users` WHERE email = :email LIMIT 1");
            $statement->bindParam(':email', $email);
            $statement->execute();

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($result)) {
                $_SESSION['error_message'] = 'User not found!';
                header('Location: /mywebapp/resetpassword.php');
                return;
            }

            $body = "Use the link below to reset your password<br> http://localhost:8888/mywebapp/changePassword.php?token=" . self::RESET_HASH . "&email=" . $email;
            sendMail(
                'Reset password',
                $body,
                $email
            );

            $_SESSION['success_message'] = 'Kindly check your email for the reset link!';
            header('Location: /mywebapp/resetpassword.php');
            return;
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    function changePassword($email, $newPassword) {
        $newEncryptedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $dbConn = new DatabaseConnection();
        $pdo = $dbConn->getConnection();

        try {
            $statement = $pdo->prepare("SELECT * FROM `users` WHERE email = :email LIMIT 1");
            $statement->bindParam(':email', $email);
            $statement->execute();

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($result)) {
                $_SESSION['error_message'] = 'User not found';
                header('Location: /mywebapp/changePassword.php');
                return;
            }

            $stmt = $pdo->prepare("UPDATE users SET password = :newPassword WHERE email = :email");
            $updated = $stmt->execute([
                ':newPassword' => $newEncryptedPassword,
                ':email' => $email
            ]);

            if ($updated === true) {
                $_SESSION['success_message'] = 'Password changed successfully';
                header('Location: /mywebapp/login.php');
                return;
            } else {
                $_SESSION['error_message'] = 'Password not updated, try again';
                header('Location: /mywebapp/changePassword.php');
                return;
            }
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            die();
        }
    }
}

$reset = new ResetPassword();

if (isset($_POST['data']['token']) && ($_POST['data']['token'] == ResetPassword::RESET_HASH)) {
    $postedData = $_POST['data'];
    $reset->changePassword($postedData['email'], $postedData['newPassword']);
    return;
}

$reset->resetPasswordRequest();