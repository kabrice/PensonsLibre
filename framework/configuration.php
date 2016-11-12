<?php
namespace F3il;
defined('__F3IL__') or die('Acces Interdit');

class Configuration {
    private static $_instance;
    private  $data;
    /**
     * 
     * @param type $fichierIni
     * @throws \F3il\Error
     */
    private function __construct($fichierIni) {    
        if(is_readable($fichierIni)){
            $this->data = parse_ini_file($fichierIni);   
        }
        else{            
       throw new \F3il\Error('Pas de fichier configuration.ini');
   }
        
    }
    /**
     * 
     * @param type $fichierIni
     * @return type
     */
    public static function getInstance($fichierIni=''){
        if(is_null(self::$_instance)==true){
            self::$_instance = new \F3il\Configuration($fichierIni);
        }    
        return self::$_instance;
    }
    /**
     * 
     * @param type $name
     * @return type
     * @throws \F3il\Error
     */
    public function __get($name) {
        if(isset($this->data[$name])){
            return $this->data[$name];
        }
        else{
            throw new \F3il\Error ("fichier" .$name. "Introuvable" );
        }
        
    }
}


