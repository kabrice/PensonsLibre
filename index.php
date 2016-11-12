<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('__PENSONSLIBRE__', '');
define('ROOT_PATH', __DIR__);
define('APPLICATION_PATH', ROOT_PATH.'/application');
define('APPLICATION_NAMESPACE','Pensonslibre');
require_once 'framework/f3il.php';
//echo \f3il\Request::get('controller','enseignant');
$app = \F3il\Application::getInstance(APPLICATION_PATH.'/configuration.ini');
$app->setAuthenticationDelegate('UtilisateurModel');
$app->setDefaultController('Index');
$app->run();
