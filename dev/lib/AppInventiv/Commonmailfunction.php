<?php
namespace AppInventiv;
use \Exception;
#require_once "vendor/autoload.php";


class Commonmailfunction
{
    //PHPMailer Object
    function send_mail($reciever_email,$data)
    {
        require_once 'PHPMailer/PHPMailerAutoload.php';
        include_once 'PHPMailer/class.phpmailer.php';
        $mail = new PHPMailer;

        //From email address and name
        $mail->From = "noreply@applaurels.com";
        $mail->FromName = "Reusable";

        //To address and name
        //$mail->addAddress($email, "Recepient Name");
        $mail->addAddress($email); //Recipient name is optional

        //Address to which recipient will reply
        $mail->addReplyTo("reply@yourdomain.com", "Reply");

        //CC and BCC
        $mail->addCC("cc@example.com");
        $mail->addBCC("bcc@example.com");

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = "Reusable Component otp";
        $mail->Body = $otp;
        $mail->AltBody = "This is the plain text version of the email content";

        if(!$mail->send()) 
        {
            //echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } 
        else 
        {
            //echo "Message has been sent successfully";
            return true;
        }
    }

}