<?php
/**
 * classe contentant la BDD
 */
namespace F3il;
    defined('__F3IL__') or die('Acces interdit');

class Database {
    private static $_instance;
    private static $db;
    
    /**
     * constructeur du Singleton
     * 
     * @param string $hostname
     * @param string $login
     * @param string $password
     * @param string $database
     * @throws Error
     */
    private function __construct($hostname, $login, $password, $database){
        try{
            self::$db = new \PDO("mysql:host=$hostname;dbname=$database",$login,$password,array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        catch(Exception $e)
        {
            throw new Error("Erreur connexion base de donnée ".$e->getMessage());
        }
    }
    
    /**
     * getInstance du Singleton Database
     * 
     * @param string $hostname
     * @param string $login
     * @param string $password
     * @param string $database
     * @return PDO
     */
    public static function getInstance($hostname='', $login='', $password='', $database=''){
        if(is_null(self::$_instance)===true){
                self::$_instance = new Database($hostname, $login, $password, $database);            
            }
            return self::$db;
    }
}
