<?php

namespace F3il;

defined('__F3IL__') or die('Acces interdit');


class CsrfHelper{
    const SESSION_KEY ='f3ilcsrfToken';

    public static function getToken(){
     if(isset($_SESSION[self::SESSION_KEY])){
         return $_SESSION[self::SESSION_KEY];
     }  else {
         $_SESSION[self::SESSION_KEY] = hash('sha256', uniqid());
         return $_SESSION[self::SESSION_KEY];
     }
}

public static function csrf(){
    ?>
<input type="hidden" name="<?php echo self::SESSION_KEY;?>" value="<?php echo self::getToken();?>">

<?php }

public static function checkToken(){
  if($_SESSION[self::SESSION_KEY] === $_POST[self::SESSION_KEY]) {
      return TRUE;
  }  else {
      return FALSE;
  }
}
}
?>