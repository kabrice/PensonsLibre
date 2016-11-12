<?php
    namespace F3il;
    
    defined('__F3IL__') or die('Acces interdit');
    
    abstract class Request
    {
        /**
         * Méthode d'accès à une donnée d'un tableau
         * @param array $array
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        private static function fetch(array $array,$key,$default=null) {
            if(isset($array[$key])){
                return $array[$key];
            }
            return $default;
        }
        
        /**
         * Méthode pour lire dans $_GET
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        public static function get($key,$default=null) {
            return self::fetch($_GET,$key,$default);
        }
        
        /**
         * Méthode pour lire dans $_POST
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        public static function post($key,$default=null) {
            return self::fetch($_POST,$key,$default);
        }
        
        /**
         * Méthode pour lire dans $_SESSION
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        public static function session($key,$default=null) { 
            return self::fetch($_SESSION,$key,$default);
        }
        
        /**
         * Indique si la requête est de type POST
         * @return boolean
         */
        public static function isPost(){
            return ($_SERVER['REQUEST_METHOD'] == 'POST');
        }                
    }
