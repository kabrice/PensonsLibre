<?php
namespace Pensonslibre;
use F3il\Messenger;

defined('__PENSONSLIBRE__') or die('Acces Interdit');
error_reporting(E_ALL);
ini_set('display_errors', 1);
class IndexController extends \F3il\Controller{

    public function __construct() {
        $this->setDefaultActionName('index');
    }

    /**
     * @throws \F3il\Error
     */
    public function indexAction()
    {

        $page = \F3il\Page::getInstance();
        if(isset($_COOKIE['email']))
        {

            \F3il\HttpHelper::redirect('?controller=utilisateur&action=posterpensee&bienvenue=connexion');
        }

        $page->setTemplate('index');
        $page->setView('index');
        $page->form_login = new LoginForm('?controller=index&action=index&bienvenue=connexion');
        $form_login = $page->form_login;
        $page->form_inscription = new NouveauutilisateurForm('?controller=index&action=index&bienvenue=inscription');
        $utilisateurModel = new utilisateurModel();

        $funct = new FunctionsHelper();






        if($_SERVER['REQUEST_METHOD']=="GET")
        {
            if(isset($_GET['email']) && isset($_GET['code']) && isset($_GET['active']))
            {
                $usermodel = new UtilisateurModel();
                $usermodel->activerutilisateur($_GET['email'],$_GET['code']);
                $_SESSION['activer']=$_GET['active'];
                \F3il\HttpHelper::redirect('?controller=index&action=index');
                exit();

            }

            return;
        }

        if (!\F3il\Request::isPost()) {

            return;
        }

        if (!\F3il\CsrfHelper::checkToken()) {
            throw new \F3il\Error("Erreur formulaire ");
        }
        $bienvenue=\F3il\Request::get('bienvenue');

        if ( $bienvenue=== "connexion") {


            $form_login->loadData($_POST);

            if (!$form_login->validate()) {
                return;
            }

            if (!\F3il\Authentication::login($form_login->email->value, $form_login->motdepasse->value)) {
                \F3il\Messenger::setMessage('Email ou mot de passe mal saisi :(');
                return;
            }

            $data = $utilisateurModel->auth_getUserByLogin($form_login->email->value);

            if (\F3il\Authentication::isAuthenticated()) {
                $_SESSION['administrateur'] = $data['administrateur'];
                if ($data['administrateur'] == 0) {
                    \F3il\HttpHelper::redirect('?controller=utilisateur&action=posterpensee&bienvenue=connexion');
                } else {
                    \F3il\HttpHelper::redirect('?controller=utilisateur&action=validerpensee&bienvenue=connexion');
                }

            }
            else{

            }

        } elseif($bienvenue === "inscription") {

            $form = $page->form_inscription;
            $utilisateurModel = new utilisateurModel();
            $form->loadData($_POST);

            if (!$form->validate()) {
                return;
            }
            $_SESSION['email_exists'] = 0;
            if ($utilisateurModel->loginUtilisateur($_POST['email']) > 0) {
                $_SESSION['email_exists'] = 1;
                \F3il\Messenger::setMessage("Email déjà utilisé");

                return;
            }
            $utilisateurModel->creerutilisateur($form->getData());
            $email3il=preg_match("/@3il\.fr$/", @$_POST['email'], $tab);
            if(isset($_POST['confirmation']) && $_POST['confirmation']==$_POST['motdepasse'] && $_SESSION['email_exists']==0 && $email3il==1) {
                //echo  "<script src=\"http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.5.min.js\" type=\"text/javascript\"></script>";
                $_SESSION['email_exists']=0;
                \F3il\HttpHelper::redirect('?controller=index&action=index');
                exit();

            }
        }else{
            echo ' ';
        }
    }


}