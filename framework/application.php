<?php    
    namespace F3il;
    
    defined('__F3IL__') or die('Acces interdit');
    
    /**
     * Classe repr�s�ntant l'application Web
     * Contr�leur frontal
     */
    class Application 
    {
        private static $_instance = null;
        private $configuration = null;
        private $currentController;
        private $currentAction;
        private $defaultController;

        /**
         * Constructeur priv� (singleton)
         * 
         * @param string $fichierIni : chemin du fichier ini
         * 
         */
        private function __construct($fichierIni) {
            $this->configuration = Configuration::getInstance($fichierIni);
          
        }
        
        
        /**
         * Fabrique et/ou retourne l'unique instance d'application
         * 
         * @param string $fichierIni : chemin du ficheir ini
         * @return Application
         */
        public static function getInstance($fichierIni='') {  
            if(is_null(self::$_instance)=== TRUE){
                self::$_instance = new \F3il\Application($fichierIni);
            }
            return self::$_instance;
        }
        
        /**
         * Lance l'ex�cution de l'application
         */
        public function run() {
            $this->currentController=\F3il\Request::get('controller', $this->defaultController);
            $classname1 = '\\'.APPLICATION_NAMESPACE.'\\'.ucfirst($this->currentController).'Controller';
            $controlleur = new $classname1();
            $this->currentAction = \F3il\Request::get('action',$controlleur->getDefaultActionName());
            $controlleur->execute($this->currentAction);
            self::getPage()->render();
        }
        
        /**
         * Retourne la configuration charg�e par l'application
         * 
         * @return Configuration
         */
        public static function getConfig() { 
            if(!is_null(self::$_instance)){
                return self::$_instance->configuration;
            }
            else{
                return null;
            }

        }
        
        /**
         * Retourne la connexion � la base de donn�e
         * 
         * @return PDO
         */
        public static function getDB() {
            
            $config = self::getConfig();
           
            return Database::getInstance
                    ($config->db_hostname,
                     $config->db_login, 
                     $config->db_password, 
                     $config->db_database);

        }
        
        /**
         * Retourne la page � afficher
         * 
         * @return Page
         */
        public static function getPage() {
            return \F3il\Page::getInstance();
        }
        
        /**
         * Setter pour le contr�leur par d�faut
         * 
         * @param string $controller
         */
        public function setDefaultController($controller) {
            $this->defaultController = $controller;
        }
        
        public function getCurrentController(){
            return \F3il\Request::get('controller');
        }
        
        public function getCurrentAction(){
           return \F3il\Request::get('action');
        }
        
        public function setAuthenticationDelegate($delegateClass){
            if(isset($delegateClass)){
                $delegateClass = '\\'.APPLICATION_NAMESPACE.'\\'.$delegateClass;
                $objdelegateClass = new $delegateClass();
                $auth = Authentication::getInstance($objdelegateClass);
            }  else {
                throw new Error("Classe delegate pas definie");
            }
        }
    }
