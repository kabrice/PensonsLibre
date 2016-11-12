<?php
namespace Pensonslibre;

use F3il\Messenger;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

class RecoverController extends \F3il\Controller{

    public function __construct() {
        $this->setDefaultActionName('recover');
    }

    public function recoverAction()
    {
        $page = \F3il\Page::getInstance();
        $page->setTemplate('apropos');
        $page->setView('recover');
        $page->form_recover = new RecoverForm('#');

        if($_SERVER['REQUEST_METHOD']=="POST")
        {  unset($_SESSION['previous']);
            if(isset($_SESSION['token']))
            {   $funct = new FunctionsHelper();
                $_SESSION['compteactiver']= $funct->token_generator();
                $email=$_POST['email'];
                $utilisateurmodel=new UtilisateurModel();

                if ($utilisateurmodel->loginUtilisateur($_POST['email']) > 0) {

                    $validation_code = md5($email.microtime());




                    $utilisateurmodel->majValidationCode($validation_code,$email);

                    $subject = "Reinitialise ton mot de passe avec ton code";
                    $msg1="Hey ! Voici ton code de réinitialisation : $validation_code

                    Clique sur ce lien pour réinitialiser ton mot de passe : https://www.thingether.com/?controller=recover&action=code&email=$email&code=$validation_code

                     ";
                    $msg2="Hey ! Voici ton code de réinitialisation : <h3>$validation_code</h3><br/>

                    Clique sur ce lien pour réinitialiser ton mot de passe :<br/> https://www.thingether.com/?controller=recover&action=code&email=$email&code=$validation_code

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
                    $mail->Body    = $msg2;
                    $mail->AltBody = $msg1;
                    $mail->SmtpClose();
                    if(!$mail->send()) {
                        echo 'Message could not be sent.';
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                    } else {


                        \F3il\HttpHelper::redirect('?controller=index&action=index');
                        setcookie('temp_access_code',$validation_code, time()+900);
                        exit();
                    }



                    return;
                }else{
                    Messenger::setMessage('Désolé, cette email n\'existe pas chez nous :(', 'danger');

                }
            }else{
                \F3il\HttpHelper::redirect('?controller=index&action=index');
                exit();
            }
        }

        if(isset($_POST['cancel_submit']))
        {
            unset($_SESSION['token']);
            \F3il\HttpHelper::redirect('?controller=index&action=index');
            exit();
        }


    }

    public function codeAction()
    {
        $page = \F3il\Page::getInstance();
        $page->setTemplate('apropos');
        $page->setView('code');
        $page->form_code = new CodeForm('#');

        if(isset($_COOKIE['temp_access_code']))
        {
            if(!isset($_GET['email'])&& !isset($_GET['code']))
            {

            }elseif(empty($_GET['email'])&& empty($_GET['code'])){
                //\F3il\HttpHelper::redirect('?controller=index&action=index');
            }else{

                if(isset($_POST['code'])){

                    $email = htmlentities($_GET['email']);
                    $validation_code= htmlentities($_POST['code']);
                    $utilmodel = new UtilisateurModel();
                    $count = $utilmodel->val_getIdByEmail($validation_code,$email);
                    //echo $count['num_utilisateur'];
                    //die();
                    if ($count['num_utilisateur'] >= 1)
                    {
                        setcookie('temp_access_code',$validation_code, time()+300);
                        \F3il\HttpHelper::redirect('?controller=recover&action=reset&email='.$email.'&code='.$validation_code);
                    }else{
                        $_SESSION['code_fail']=1;

                    }
                }
            }


        }else{
            $funct=new FunctionsHelper();
            $_SESSION['expire']= $funct->token_generator();
            \F3il\HttpHelper::redirect('?controller=index&action=index&expire='.$_SESSION['expire']);
            exit();
        }

    }

    public function resetAction()
    {
        $page = \F3il\Page::getInstance();
        $page->setTemplate('apropos');
        $page->setView('reset');
        $page->form_reset = new ResetForm('#');

        $funct = new FunctionsHelper();
        if (isset($_COOKIE['temp_access_code']))
        {

            if (isset($_GET['email']) && $_GET['code'])
            {

                if (isset($_SESSION['token']))
                {
                    unset($_SESSION['token']);
                    if(@$_POST['password']===@$_POST['confirm_password'] && isset($_POST['password']) && strlen($_POST['password'])>=6)
                    {
                        $utimodel = new UtilisateurModel();
                        $count = $utimodel->loginUtilisateur($_GET['email']);

                        if ( $count > 0) {

                            $form=$page->form_reset;
                            $utimodel->majMotdepasse($_POST['password'],$_GET['email'], $form->getData());

                            $_SESSION['reset'] = $funct->token_generator();
                            \F3il\HttpHelper::redirect('?controller=index&action=index&reset='.$_SESSION['reset']);
                        }else{
                            ;
                            $_SESSION['erreur_password']=1;
                        }

                    }elseif(strlen(@$_POST['password'])<6 && isset($_POST['password'])){
                        Messenger::setMessage('Mot de passe trop court :(');
                    }elseif(@$_POST['password']!=@$_POST['confirm_password'] && isset($_POST['password'])){
                        Messenger::setMessage('Le champ confirmation ne correspond pas :(');
                    }else{
                        if(isset($_POST['password']))
                            $_SESSION['confirm_dif']=1;
                    }

                }
            }
        }else {
            $_SESSION['expire'] = $funct->token_generator();
            \F3il\HttpHelper::redirect('?controller=recover&action=recover&expire=' . $_SESSION['expire']);
            exit();
        }
    }

}