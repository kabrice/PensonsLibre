<?php

namespace F3il;

defined('__F3IL__') or die('Acces interdit');

class Authentication{
    protected $user;
    protected $authModel;
    private static $_instance;
    const SESSION_KEY = "f3il.authentication";
    
    public function __construct(AuthenticationDelegate $authModel ) {
        $this->authModel = $authModel;
    }
    
    public static function getInstance(AuthenticationDelegate $authModel = NULL){
      if(!is_null($authModel)){
          self::$_instance = new Authentication($authModel);
          self::$_instance->loadUserData();
          return self::$_instance;
      }  else {
          throw new Error("l'objet AuthentificationDelegate est null");
      }
    }

    /**
     * 
     * @param type $password
     * @param type $salt
     * @return type
     */
    public static function hash($password,$salt){
        return hash('sha256',  hash('sha256', $salt).$password);
    }
    
    public static function login($login,$password){
        $saltCol = self::$_instance->authModel->auth_getSaltColumn();
        $passwordCold = self::$_instance->authModel->auth_getPasswordColumn();
        $idCol = self::$_instance->authModel->auth_getIdColumn();
        $user = self::$_instance->authModel->auth_getUserByLogin($login);
        if(self::hash($password, $user[$saltCol]) != $user[$passwordCold]) return FALSE;
        $_SESSION[self::SESSION_KEY]=$user[$idCol];
        return true;
        }
        
    public function loadUserData(){
        $userId = Request::session(self::SESSION_KEY);
        if(is_null($userId))            return;
        $this->user = $this->authModel->auth_getUserById($userId);
    }
    
    public static function logout(){
        self::$_instance->user = NULL;
        unset($_SESSION[self::SESSION_KEY]);
    }
    
    public static function isAuthenticated(){
        return (isset($_SESSION[self::SESSION_KEY]));
    }
    
    public static function getUserData(){
        return self::$_instance->user;
    }
    
    public static function getUserId(){
        $idCol = self::$_instance->authModel->auth_getIdColumn();
        return self::$_instance->user[$idCol];
    }
}

