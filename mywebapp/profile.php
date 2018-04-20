<?php
require_once ('protected_access_check.php');
require_once ('application/models/User.php');

/**
 * @var $userData User
 */
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>::Profile::</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="stylesheet" href="/mywebapp/css/main.css" type="text/css">
    <link rel="shortcut icon" href="/mywebapp/images/favicon.ico?v=2" type="image/x-icon"/>
</head>
<body>
<div id="wrapper">
    <div id="maincontent">

        <div id="header">
            <div id="logo" class="left">
                <a href="/mywebapp">ICD0007</a>
            </div>
            <div class="right marT10">
                <b>
                    <a href="/mywebapp/logout.php">Logout</a>
                </b>
            </div>
            <br><br>
            <ul class="topmenu">
                <li><a href="/mywebapp">Home</a></li>
                <li><a href="/mywebapp">Student Lists</a></li>
                <li><a href="/mywebapp">Contact Us</a></li>
            </ul>
            <br>
            <div class="banner"><p></p></div>
            <br class="clear"/>
        </div>

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
                <form id="updateForm" action="application/updateHandler.php" method="POST" enctype="multipart/form-data">
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
                        <label>Profile Picture: </label>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                    <p>
                    <p>
                        <input type="submit" name="btnSubmit" value="Update profile" class="button marL10"/>
                    <p>
                </form>
                <div id="profileImage">
                    <img src="<?= $userData['profile_avatar'] ?>">
                </div>
            </div>
        </div>

    </div><!-- maincontent -->
    <br>
    <div id="footer">
        <div class="footer">
            Copyright &copy; 2018 ICD0007. <br/>
            <a href="/mywebapp">Home</a> | <a href="about">About Us</a> | <a href="contact">Contact Us</a> <br/>
            <span class="contact">Tel: +372-1111111&nbsp;
			Email:icd007@icd0007.com</span>
        </div>
    </div><!-- footer -->

</div><!-- wrapper -->

</body>
</html>

