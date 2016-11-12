<?php
namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

class AproposController extends \F3il\Controller{

    public function __construct() {
        $this->setDefaultActionName('apropos');
    }

    public function aproposAction()
    {
        $page = \F3il\Page::getInstance();
        $page->setTemplate('apropos');
        $page->setView('apropos');

    }

}