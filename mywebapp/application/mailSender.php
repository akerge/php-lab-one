<?php

require_once ("../vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;

function sendMail($subject, $body, $recipientAddress)
{
    $mailer = new PHPMailer();

    try {
        $mailer->IsSMTP();

        $mailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mailer->Host = 'ssl://smtp.gmail.com:465';

        $mailer->SMTPAuth = TRUE;
        $mailer->Username = 'yourmail@gmail.com';  // Change this to your gmail address
        $mailer->Password = 'xxxxxxx';  // Change this to your gmail password
        $mailer->From = 'youremail@gmail.com';  // This HAVE TO be your gmail address
        $mailer->FromName = 'ICD0007';
        $mailer->Body = $body;
        $mailer->Subject = $subject;
        $mailer->AddAddress($recipientAddress);

        if(!$mailer->Send()) {
            return "Message was not sent";
        } else {
            return "Message has been sent";
        }
    } catch (Exception $e) {
        // for debugging purpose
        var_dump('Message could not be sent. Mailer Error: ', $mailer->ErrorInfo);
        die();
    }
}