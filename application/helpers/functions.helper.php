<?php
namespace Pensonslibre;
use F3il\Messenger;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

class FunctionsHelper
{


    public function token_generator()
    {
        $token= $_SESSION['token']=md5(uniqid(mt_rand(), true));
        return $token;
    }


    function sweetActivation($filename)
    {

        $sweetAlert = <<<DELIMITER

    <script type="text/javascript" src="js/$filename.js"></script>

DELIMITER;
        echo  $sweetAlert;

    }



    public function send_email_activation($email, $validation_code)
    {
        $subject = "Confirme ton compte sur Thingether";
        $msg = "Hey, clique juste sur le lien ci après pour activer ton compte :)

            https://www.thingether.com/?controller=index&action=index&email=$email&code=$validation_code&active=$session_active
            ";


        require "./library/PHPMailer/PHPMailerAutoload.php";

        $mail = new \PHPMailer();

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'localhost';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'kabrice';                 // SMTP username
        $mail->Password = 'ADieulagloire7';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('noreply@thingether.com', 'Thingether');
        $mail->addAddress($email);


        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $msg;
        $mail->AltBody = $msg;
        $mail->SmtpClose();
        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }




}

