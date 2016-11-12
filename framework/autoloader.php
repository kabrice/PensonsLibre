<?php      
    namespace F3il;
    
    defined('__F3IL__') or die('Acces interdit'.__FILE__);
    
    /**
     * Autoloader : classe de chargement automatique des classes du framework
     * et de l'application
     */
    class AutoLoader {
        private static $_instance;
        
        /**
         * Chemin vers le dosser de l'application
         * @var string
         */
        private $appFolder;
        
        /**
         * Dossier du framework
         * @var type          
         */
        private $frameworkFolder;
        
        /**
         * Identifiant de l'espace de nom de l'application
         * @var string
         */
        private $appNamespace;
        
        /**
         * Identifiant de l'espace de nom du framework
         * @var string 
         */
        private $frameworkNamespace;
        
        /**
         * Constructeur
         * 
         * @param string $appFolder : chemin vers le dossier de l'application
         * @param string $appNamespace : identifiant de l'espace de nom de l'application
         */
        private function __construct($appFolder,$appNamespace) {
            
            $this->appFolder = $appFolder;
            $this->frameworkFolder = __DIR__;
            $this->appNamespace = strtolower($appNamespace);
            $this->frameworkNamespace = strtolower(__NAMESPACE__);
                              
            spl_autoload_register(array($this,'loader'));           
        }
        
        /**
         * Gestion du singleton
         * 
         * @param string $appFolder : chemin vers le dossier de l'application
         * @param string $appNamespace : identifiant de l'epsace de nom de l'application
         * @return type
         */
        public static function getInstance($appFolder,$appNamespace) {
            if(is_null(self::$_instance)===true){
                self::$_instance = new AutoLoader($appFolder,$appNamespace);            
            }
            return self::$_instance;
        }
        
       /**
        * Réalisé l'inclusion du fichier s'il existe
        * 
        * @param string $filename : chemin du fichier à inclure
        * @param string $classname : nom de la classe à vérifier une fois le fichier inclus
        * @throws \F3il\Error
        */
        public function checkAndRequire($filename,$classname) {
            if(!is_readable($filename)) throw new Error('Fichier inexistant '.$filename);            
            require_once $filename;
            
            if(!class_exists($classname)&&!interface_exists($classname)) throw new Error('Classe '.$classname.' non trouvée dans le fichier '.$filename);            
        }
        
        /**
         * Détermine le chemin et le nom de la classe à inclure suivant les différents cas
         * 
         * @param string$ className : nom de la classe à charger
         */
        private function loader($className) { 
            // Elimine les \ dans le nom de classe 
            $class = str_replace('\\', '', $className);                      
            
            // Découpe le nom suivant les majuscules pour faire un tableau de chaînes
            $parts = preg_split('/(?=[A-Z])/', $class, -1, PREG_SPLIT_NO_EMPTY);
            
            // Convertit toutes les chaînes en minuscules            
            $parts = array_map('strtolower',$parts); 
            
            switch($parts[0]){
                // Classe du framework
                case $this->frameworkNamespace:                    
                    if(end($parts)==='helper'){
                       $this->checkAndRequire($this->frameworkFolder.'/helpers/'.$parts[1].'.helper.php',$className);
                    } else {
                        if(count($parts)==2){
                            $this->checkAndRequire($this->frameworkFolder.'/'.$parts[1].'.php',$className);
                        } else {
                            $this->checkAndRequire($this->frameworkFolder.'/'.$parts[1].'.'.$parts[2].'.php',$className);
                        }
                    }
                    break;
                // Classe de l'application
                case $this->appNamespace:                                        
                    $this->checkAndRequire($this->appFolder.'/'.$parts[2].'s/'.$parts[1].'.'.$parts[2].'.php',$className);
                    break;
            }
        }
       
    }
   

