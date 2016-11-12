<?php

namespace F3il;

defined('__F3IL__') or die('Acces Interdit');

class Error extends \Exception {

    /**
     * 
     * @param type $message
     */
    public function __construct($message) {
        parent::__construct($message);
    }

    /**
     * rendre le frame
     */
    public function render() {
        //echo $this->message;
        require_once ('framework/errors/error_debug.php');
        return '';
        
    }

    /**
     * methode pour gerer les erreurs
     */
    public function __toString() {
        $varconfi = \F3il\Application::getConfig();

        if (!is_null($varconfi)&&$varconfi->debugMode !== "production") {
            $this->render();
        } else {
            echo file_get_contents('framework/errors/error_prod.php');
        }

    }

}
