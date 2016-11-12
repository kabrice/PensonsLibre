<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

class AdminModel implements \F3il\AuthenticationDelegate
{
    public function lire($num_utilisateur, $administrateur)
    {
        $db = \F3il\Application::getDB();

        $sql = "SELECT email"
            . "FROM utilisateur"
            . "WHERE num_utilisateur = :num_utilisateur "
            . "AND administrateur = :administrateur";
        $req = $db->prepare($sql);

        $sql->bindValue(':num_utilisateur', $num_utilisateur, \PDO::PARAM_INT);
        try {
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }

        return $req->fetch(\PDO::FETCH_ASSOC);
    }


    public function loginUtilisateur($email)
    {
        $db = \F3il\Application::getDB();
        $sql = "SELECT count(*) from utilisateur where email = :email";
        $req = $db->prepare($sql);

        try {
            $req->bindValue(':email', $email);
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }

        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function creerutilisateur(array $data)
    {
        $db = \F3il\Application::getDB();
        $sql = "INSERT INTO utilisateur SET "
            . " email = :email"
            . ", motdepasse = :motdepasse"
            . ", creation = :creation";
        $req = $db->prepare($sql);
        $salt = date('Y-m-d H:i:s');
        $req->bindValue(':email', $data['email']->value);
        $req->bindValue(':motdepasse', \F3il\Authentication::hash($data['motdepasse']->value, $salt));
        $req->bindValue(':creation', $salt);
        try {
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }
    }


    public function auth_getUserByLogin($email)
    {
        $db = \F3il\Application::getDB();
        $sql = "select * from utilisateur where email = :email";
        $req = $db->prepare($sql);
        try {
            $req->bindValue(':email', $email);
            $req->execute();
        } catch (Exception $ex) {
            throw new \F3il\Error("Erreur SQL" . $ex->getMessage());
        }
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function auth_getIdColumn()
    {
        return 'NUM_UTILISATEUR';
    }

    public function auth_getLoginColumn()
    {
        return 'EMAIL';
    }

    public function auth_getPasswordColumn()
    {
        return 'MOTDEPASSE';
    }

    public function auth_getSaltColumn()
    {
        return 'CREATION';
    }

    public function auth_getUserById($id)
    {
        $db = \F3il\Application::getDB();
        $sql = "select * from utilisateur where num_utilisateur = :num_utilisateur";
        $req = $db->prepare($sql);
        try {
            $req->bindValue(':num_utilisateur', $id);

            $req->execute();
        } catch (Exception $ex) {
            throw new \F3il\Error("Erreur SQL" . $ex->getMessage());
        }
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPenseeAdmin()
    {
        $db = \F3il\Application::getDB();

        $sql = "SELECT  * "
            . "FROM `pensee`"
            . "natural JOIN `utilisateur` "
            . "natural JOIN `annee_etude`  "
            . "WHERE `pensee`.`VALIDER_PENSEE` = 0";

        $req = $db->prepare($sql);
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function validerPensee($num_pensee)
    {
        $db = \F3il\Application::getDB();

        $sql = "UPDATE pensee SET VALIDER_PENSEE=1 WHERE NUM_PENSEE = :NUM_PENSEE";

        $req = $db->prepare($sql);


        try{
            $req->bindValue(':NUM_PENSEE', $num_pensee);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }


    }

}