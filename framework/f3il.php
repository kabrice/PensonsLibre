<?php
namespace F3il;
session_start();
$_SESSION['ok']=12;

if(!defined('APPLICATION_NAMESPACE')) throw  new Error('Acces Interdit');
define('__F3IL__', '');
if(!defined('ROOT_PATH')) throw  new Error ('Acces Interdit') ;
if(!defined('APPLICATION_PATH')) throw  new Error ('Acces Interdit');
require_once 'autoloader.php';
AutoLoader::getInstance( APPLICATION_PATH,APPLICATION_NAMESPACE);
?>
