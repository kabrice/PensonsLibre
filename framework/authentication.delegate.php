<?php

namespace F3il;

defined('__F3IL__') or die('Acces Interdit');


interface AuthenticationDelegate{
    public function auth_getLoginColumn();
    public function auth_getPasswordColumn();
    public function auth_getSaltColumn();
    public function auth_getIdColumn();
    public function auth_getUserByLogin($login);
    public function auth_getUserById($id);
}
