<?php
namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

class mdpController extends \F3il\Controller{

    public function __construct() {
        $this->setDefaultActionName('suggestion');
    }

    public function suggestionAction()
    {
        $page = \F3il\Page::getInstance();
        $page->setTemplate('header');
        $page->setView('profil');
        $page->form_suggestion = new SuggestionForm('?controller=utilisateur&action=posterpensee', \F3il\Form::CREATE_MODE);
        $form_poster=$page->form_suggestion;


        if (!\F3il\Authentication::isAuthenticated()) {
            \F3il\HttpHelper::redirect('?controller=index&action=index');
        }
    }

}