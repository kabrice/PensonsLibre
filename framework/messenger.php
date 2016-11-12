<?php

namespace F3il;

defined('__F3IL__') or die('Acces Interdit');

class Messenger{
    const SESSION_KEY = 'f3il.messenger';
    public static function setMessage($message){
        $_SESSION[self::SESSION_KEY] = htmlspecialchars($message);
        
    }
    
    public static function hasMessage(){
        if(isset($_SESSION[self::SESSION_KEY])){
            return TRUE;
        }  else {
           return FALSE; 
        }
    }
    
    public static function getMessage(){
        $message;
        if(isset($_SESSION[self::SESSION_KEY])){
           $message =  $_SESSION[self::SESSION_KEY];
           unset($_SESSION[self::SESSION_KEY]);
           return $message;
        }  else {
            $message = "";
            return $message;
        }
        
    }
}
