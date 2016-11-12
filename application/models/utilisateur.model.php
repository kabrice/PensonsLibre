<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

class UtilisateurModel implements \F3il\AuthenticationDelegate
{
    private $former_vote;

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
        $sql = "SELECT count(*) as nombre from utilisateur where email = :email";
        $req = $db->prepare($sql);

        try {
            $req->bindValue(':email', $email);
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }

        $data=$req->fetch(\PDO::FETCH_ASSOC);
        return $data['nombre'];
    }

    public function creerutilisateur(array $data)
    {

        $db = \F3il\Application::getDB();
        $sql = "INSERT INTO utilisateur SET "
            . " email = :email"
            . ", motdepasse = :motdepasse"
            . ", NUM_ANNEE_ETUDE= :NUM_ANNEE_ETUDE"
            . ", VALIDATION_CODE= :VALIDATION_CODE"
            . ", creation = :creation";
        $req = $db->prepare($sql);
        $salt = date('Y-m-d H:i:s');
        $req->bindValue(':email', $data['email']->value);
        $req->bindValue(':VALIDATION_CODE', \F3il\Authentication::hash($data['email']->value, $salt));
        $req->bindValue(':motdepasse', \F3il\Authentication::hash($data['motdepasse']->value, $salt));
        $req->bindValue(':NUM_ANNEE_ETUDE', $data['NUM_ANNEE_ETUDE']->value);
        $req->bindValue(':creation', $salt);

        $funct = new FunctionsHelper();
        $funct->send_email_activation($data['email']->value, \F3il\Authentication::hash($data['email']->value, $salt));

        try {
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }
        return true;
    }


    public function auth_getUserByLogin($email)
    {
        $db = \F3il\Application::getDB();
        $sql = "select * from utilisateur where email = :email AND active=1";
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

    public function posterPensee(array $data)
    {
        $db = \F3il\Application::getDB();
        $date = date('Y-m-d H:i:s');
        $sql = "select count(*) as count from pensee where TITRE_PENSEE=:TITRE_PENSEE ";
        $req = $db->prepare($sql);
        $req->bindValue(':TITRE_PENSEE',$data['textarea']->value);
        $req->execute();
        $arrayPensee=$req->fetch(\PDO::FETCH_ASSOC);

        if($arrayPensee['count']>=1)
        {
            return false;
        }
        $sql = "INSERT INTO pensee SET "
            ." NUM_UTILISATEUR = :NUM_UTILISATEUR"
            .",TITRE_PENSEE=:TITRE_PENSEE"
            .",LIBELLE_PENSEE=:LIBELLE_PENSEE"
            .",PHOTO_PENSEE=:PHOTO_PENSEE"
            . ", DATE_PENSEE=:DATE_PENSEE"
        ;
        $req = $db->prepare($sql);
        /*var_dump($data);
        exit();*/
        $req->bindValue(':NUM_UTILISATEUR',  \F3il\Authentication::getUserId());
        $req->bindValue(':TITRE_PENSEE',$data['textarea']->value);
        $req->bindValue(':LIBELLE_PENSEE',$data['textareaEd']->value);
        $req->bindValue(':PHOTO_PENSEE',$data['photo']->value);
        $req->bindValue(':DATE_PENSEE',$date);

        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        return true;
    }

    public function posterContribution(array $data, $idUserOnline)
    {
        $db = \F3il\Application::getDB();
        $sql = "INSERT INTO contribution SET"
            ." NUM_UTILISATEUR = :NUM_UTILISATEUR"
            .",NUM_PENSEE=:NUM_PENSEE"
            .",LIBELLE_CONTRIBUTION=:LIBELLE_CONTRIBUTION"
            .",TYPE_CONTRIBUTION=:TYPE_CONTRIBUTION";
        $req = $db->prepare($sql);
        $req->bindValue(':NUM_UTILISATEUR',$data['NUM_UTILISATEUR']);
        $req->bindValue(':NUM_PENSEE',$data['NUM_PENSEE']);
        $req->bindValue(':LIBELLE_CONTRIBUTION',$data['LIBELLE_CONTRIBUTION']);
        $req->bindValue(':TYPE_CONTRIBUTION',$data['TYPE_CONTRIBUTION']);

        try{
           $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        $sql = "SELECT LAST_INSERT_ID() AS NUM_CONTRIBUTION";
        $req = $db->prepare($sql);
        $req->execute();
        $idLiveContribution = $req->fetch(\PDO::FETCH_ASSOC);
        $idLiveContribution = $idLiveContribution["NUM_CONTRIBUTION"];
        $data_pensee = $this->getPenseeFromContribution($idLiveContribution);
        $num_pensee_contribution = $data_pensee["NUM_PENSEE"];
        $live_notifs_params = array(
            "NUM_CONTRIBUTION"=>0,
            "NUM_UTILISATEUR" =>$idUserOnline,
            "NUM_PAGE"=>$data["NUM_PAGE"],
            "NUM_COMMENTAIRE"=>0,
            "NUM_PENSEE" =>$num_pensee_contribution,
            "NUM_VOTE"=>0,
            "NUM_CONTRIBUTION_FILS"=>$idLiveContribution,
        );
        $this->sendNotification($live_notifs_params);
        return true;
    }

    //Gestion des notifications
    /**
     *Tous les types de notification
    //User a contribué ou voté sur une pensée
    1->Un 3il (UserX) a apporté une contribution à ta pensée :
    11->Un 3il(user) a voté ta pensée :

    //User a commenté ou voté une contribution
    2->Un 3iL(userX) a commenté ta contribution :
    22->Un 3il(userX) te donne raison sur ta contribution :
    221->Un 3il(userX) a commenté un contenu de ta pensée :

    //User a repondu ou voté un commentaire
    3->Un 3il(userX) a répondu à ton commentaire :
    33->Un 3il(userX) te donne raison sur ton commentaire :
    321->Un 3il(userX) a commenté un contenu de ta pensée :

    //User a aussi comme toi voté une pensée ou contribué ou commenté
    4-> 3il(userX)  a aussi comme toi voté la pensée :
    44->Un 3il(userX)  a aussi comme toi apporté une contribution à la pensée :
    444->Un 3il(userX)  a aussi comme toi commenté la contribution  :
     * @param array $live_notifs_params
     * @throws \F3il\Error
     */
    public function sendNotification(array $live_notifs_params)
    {

        $idUserNotifFrom="";
        $code_notifier = 0;
        $code_aussi = 0;
        $usersAussi=array();

        /*print_r($live_notifs_params);*/

        //Dans le cas où user retire son vote d'une contribution ou d'un commentaire

        if($this->isNotifVoteExiste($live_notifs_params["NUM_VOTE"])){

            //Gestion specifique des votes sur les pensées
            if(isset($live_notifs_params["VOTE"]) && $live_notifs_params["VOTE"]!=0){
                $valeur_vote_notif = $this->getVoteFromRef($live_notifs_params["NUM_VOTE"], "notification");
                $valeur_vote = $this->getVoteFromRef($live_notifs_params["NUM_VOTE"], "vote");

                if($valeur_vote != $valeur_vote_notif){

                    //Faire une mise à jour dans notification et informer le user concerné du changement (avec la date)
                    $this->updateVoteInNotification($live_notifs_params["NUM_VOTE"], $live_notifs_params["VOTE"]);
                    //Changer le code et la date du vote dans notifier
                    $this->updateVoteInNotifier($live_notifs_params["NUM_VOTE"]);
                }else{
                    $this->deleteNotifVote($live_notifs_params["NUM_VOTE"]);
                }
                return;
            }
            $this->deleteNotifVote($live_notifs_params["NUM_VOTE"]);
            return;
        }else{

            $live_num_notif = $this->insertNotification($live_notifs_params);


            //User ne peut pas s'envoyer lui-même ses notifications mais peut néamoins signaler les users concernés de ses actions

            if($live_notifs_params["NUM_CONTRIBUTION"]==0 && $live_notifs_params["NUM_UTILISATEUR"]==$this->getUserIDFromRefID($live_notifs_params["NUM_PENSEE"], "pensee")){
                $code_aussi = ($live_notifs_params["NUM_CONTRIBUTION"]==0 && $live_notifs_params["NUM_VOTE"]==0) ? 44 : 4;
                $usersAussi = ($code_aussi == 4) ? $this->getUsersAussiVotesPensee($live_notifs_params["NUM_PENSEE"], $live_notifs_params["NUM_UTILISATEUR"], "NUM_PENSEE", 11) : $this->getUsersAussiContribuesPensee($live_notifs_params["NUM_PENSEE"],$live_notifs_params["NUM_UTILISATEUR"],1);

                goto aussi;
            }elseif($live_notifs_params["NUM_COMMENTAIRE"]==0 && $live_notifs_params["NUM_CONTRIBUTION"]!=0
                && $live_notifs_params["NUM_UTILISATEUR"]==$this->getUserIDFromRefID($live_notifs_params["NUM_CONTRIBUTION"],"contribution"))
            {

                $code_aussi=444;
                if($live_notifs_params["NUM_VOTE"]!=0) {
                    $this->deleteNotifVote($live_notifs_params["NUM_VOTE"]);
                    return;
                }
                $usersAussi=$this->getUsersAussiCommentesContribution($live_notifs_params["NUM_CONTRIBUTION"],$live_notifs_params["NUM_UTILISATEUR"]);
                goto aussi;
            }elseif($live_notifs_params["NUM_COMMENTAIRE"]!=0
                && $live_notifs_params["NUM_UTILISATEUR"]==$this->getUserIDFromRefID($live_notifs_params["NUM_COMMENTAIRE"],"commentaire"))
            {
                $code_aussi=444;
                if($live_notifs_params["NUM_VOTE"]!=0) {
                    $this->deleteNotifVote($live_notifs_params["NUM_VOTE"]);
                    return;
                }
                $usersAussi=$this->getUsersAussiCommentesContribution($live_notifs_params["NUM_CONTRIBUTION"],$live_notifs_params["NUM_UTILISATEUR"]);
                goto aussi;
            }


        }

        //User a contribuer ou voté sur une pensée
        if($live_notifs_params["NUM_COMMENTAIRE"]==0 && $live_notifs_params["NUM_CONTRIBUTION"]==0){
            $idUserNotifFrom = $this->getUserIDFromRefID($live_notifs_params["NUM_PENSEE"], "pensee");
            $code_notifier=1;
            if($live_notifs_params["NUM_VOTE"]!=0){
                $code_aussi=4;
                $code_notifier=11;
                $usersAussi = $this->getUsersAussiVotesPensee($live_notifs_params["NUM_PENSEE"], $live_notifs_params["NUM_UTILISATEUR"], "NUM_PENSEE", 11);
            }else{
                $live_notifs_params["NUM_CONTRIBUTION"]=(isset($live_notifs_params["NUM_CONTRIBUTION_FILS"]))?$live_notifs_params["NUM_CONTRIBUTION_FILS"]:0;

                $code_aussi=44;
                $usersAussi = $this->getUsersAussiContribuesPensee($live_notifs_params["NUM_PENSEE"],$live_notifs_params["NUM_UTILISATEUR"],1);
            }

            //User a commenté ou voté une contribution
        }else if($live_notifs_params["NUM_COMMENTAIRE"]==0 && $live_notifs_params["NUM_CONTRIBUTION"]!=0){
            $idUserNotifFrom = $this->getUserIDFromRefID($live_notifs_params["NUM_CONTRIBUTION"], "contribution");
            $code_aussi=444;
            if($live_notifs_params["NUM_VOTE"]!=0){
                $code_notifier=22;
                //$code_aussi=0;

                $this->insertIntoNotifier($idUserNotifFrom, $live_num_notif, $live_notifs_params["NUM_PENSEE"],
                     $live_notifs_params["NUM_CONTRIBUTION"], 0, $code_notifier);
                return;
               // $usersVoteSamePensee = $this->getUsersVoteSameRef($live_notifs_params["NUM_CONTRIBUTION"], $live_notifs_params["NUM_UTILISATEUR"],"NUM_CONTRIBUTION");
            }else{
                $live_notifs_params["NUM_COMMENTAIRE"]=(isset($live_notifs_params["NUM_COMMENTAIRE_FILS"]))?$live_notifs_params["NUM_COMMENTAIRE_FILS"]:0;
                $code_notifier = 221;
                $usersAussi=$this->getUsersAussiCommentesContribution($live_notifs_params["NUM_CONTRIBUTION"],$live_notifs_params["NUM_UTILISATEUR"]);
            }
                //Notifier l'auteur de la pensée
            $num_commentaire=(isset($live_notifs_params["NUM_COMMENTAIRE_FILS"]))?$live_notifs_params["NUM_COMMENTAIRE_FILS"]:0;
            $idUserPenseeFrom = $this->getUserIDFromRefID($live_notifs_params["NUM_PENSEE"], "pensee");
            if($idUserPenseeFrom != $live_notifs_params["NUM_UTILISATEUR"]) {
                $this->insertIntoNotifier($idUserPenseeFrom, $live_num_notif, $live_notifs_params["NUM_PENSEE"],
                    $live_notifs_params["NUM_CONTRIBUTION"], $num_commentaire, $code_notifier);
            }
            if($live_notifs_params["NUM_VOTE"]!=0) return; //Verifiez ça (12 / 09 / 2016 - 17:17)
            $code_notifier=2;

            //User a repondu ou voté un commentaire
        }else if($live_notifs_params["NUM_COMMENTAIRE"]!=0){

            $idUserNotifFrom = $this->getUserIDFromRefID($live_notifs_params["NUM_COMMENTAIRE"], "commentaire");
            $code_aussi=444;

            if($live_notifs_params["NUM_VOTE"]!=0){
                $code_notifier=33;

                //$code_aussi=0;
                $this->insertIntoNotifier($idUserNotifFrom, $live_num_notif, $live_notifs_params["NUM_PENSEE"],
                         $live_notifs_params["NUM_CONTRIBUTION"], $live_notifs_params["NUM_COMMENTAIRE"], $code_notifier);
                return;

                //$usersVoteSamePensee = $this->getUsersVoteSameRef($live_notifs_params["NUM_PENSEE"], $live_notifs_params["NUM_UTILISATEUR"], "NUM_PENSEE");
            }else{
                $code_notifier = 321;
                $usersAussi=$this->getUsersAussiCommentesContribution($live_notifs_params["NUM_CONTRIBUTION"],$live_notifs_params["NUM_UTILISATEUR"]);
            }
            $num_contribution=(isset($live_notifs_params["NUM_CONTRIBUTION"]))?$live_notifs_params["NUM_CONTRIBUTION"]:0;
            $num_commentaire=(isset($live_notifs_params["NUM_COMMENTAIRE"]))?$live_notifs_params["NUM_COMMENTAIRE"]:0;
            $idUserPenseeFrom = $this->getUserIDFromRefID($live_notifs_params["NUM_PENSEE"], "pensee");
            if($idUserPenseeFrom != $live_notifs_params["NUM_UTILISATEUR"]) {
                $this->insertIntoNotifier($idUserPenseeFrom, $live_num_notif, $live_notifs_params["NUM_PENSEE"],
                    $num_contribution, $num_commentaire, $code_notifier);
            }
            if($live_notifs_params["NUM_VOTE"]!=0) return; //Verifiez ça (12 / 09 / 2016 - 17:17)
            $code_notifier=3;
        }

                //Notier l'utilisateur concerné
        //echo $live_notifs_params["NUM_CONTRIBUTION"];

        $this->insertIntoNotifier($idUserNotifFrom, $live_num_notif, $live_notifs_params["NUM_PENSEE"],
            $live_notifs_params["NUM_CONTRIBUTION"], $live_notifs_params["NUM_COMMENTAIRE"], $code_notifier);
        aussi:

        if($code_aussi!=0) {
            if(empty($usersAussi)){
                return;
            }
            $commentaire_fils=(isset($live_notifs_params["NUM_COMMENTAIRE_FILS"]))?$live_notifs_params["NUM_COMMENTAIRE_FILS"]:0;

            //$live_notifs_params["NUM_CONTRIBUTION"]=(isset($live_notifs_params["NUM_CONTRIBUTION_FILS"]))?$live_notifs_params["NUM_CONTRIBUTION_FILS"]:0;
        if(isset($live_notifs_params["NUM_CONTRIBUTION_FILS"])) $live_notifs_params["NUM_CONTRIBUTION"] = $live_notifs_params["NUM_CONTRIBUTION_FILS"];
            foreach ($usersAussi as $us => $userAussi) {
                if($userAussi["NUM_UTILISATEUR"]!=$idUserNotifFrom) {
                    $this->insertIntoAussi($live_num_notif, $userAussi["NUM_UTILISATEUR"], $live_notifs_params["NUM_PENSEE"],
                        $live_notifs_params["NUM_CONTRIBUTION"], $code_aussi, $commentaire_fils);
                }
            }
        }
    }


    private function updateVoteInNotification($num_vote, $vote){
        $db = \F3il\Application::getDB();

        $sql = "UPDATE notification SET VOTE=:VOTE, DATE_NOTIFICATION=:DATE_NOTIFICATION WHERE NUM_VOTE = :NUM_VOTE";
        $date = date('Y-m-d H:i:s');
        $req = $db->prepare($sql);
        $req->bindValue(':VOTE', $vote);
        $req->bindValue(':DATE_NOTIFICATION', $date);
        $req->bindValue(':NUM_VOTE', $num_vote);
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
    }

    private function updateVoteInNotifier($num_vote){
        $db = \F3il\Application::getDB();

        $sql = "UPDATE notifier
                INNER JOIN notification
                      ON notifier.NUM_NOTIFICATION = notification.NUM_NOTIFICATION
                SET notifier.CODE_NOTIF=5, notifier.VU=0, notifier.LU=0, notification.DATE_NOTIFICATION=:DATE_NOTIFICATION, notifier.DATE_NOTIFIER=:DATE_NOTIFIER
                WHERE notification.NUM_VOTE = :NUM_VOTE";
        $date = date('Y-m-d H:i:s');
        $req = $db->prepare($sql);
        $req->bindValue(':DATE_NOTIFICATION', $date);
        $req->bindValue(':DATE_NOTIFIER', $date);
        $req->bindValue(':NUM_VOTE', $num_vote);
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
    }

    private function getVoteFromRef($num_vote, $ref){
        $db = \F3il\Application::getDB();

        $sql="SELECT VOTE
              FROM $ref
              WHERE NUM_VOTE=:NUM_VOTE";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_VOTE',$num_vote);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $valeur_vote = $req->fetch(\PDO::FETCH_ASSOC);
        return $valeur_vote["VOTE"];
    }



    private function insertIntoAussi($num_notif, $num_user, $num_pensee, $num_contribution,$code_aussi,$commentaire_fils )
    {
        //print_r($num_user);
        $db = \F3il\Application::getDB();
        $sql = "INSERT INTO aussi SET"
            ." NUM_NOTIFICATION=:NUM_NOTIFICATION"
            .",NUM_UTILISATEUR=:NUM_UTILISATEUR"
            .",NUM_PENSEE=:NUM_PENSEE"
            .",NUM_CONTRIBUTION=:NUM_CONTRIBUTION"
            .",CODE_AUSSI=:CODE_AUSSI"
            .",NUM_COMMENTAIRE=:NUM_COMMENTAIRE";
        $req = $db->prepare($sql);
        $req->bindValue(':NUM_NOTIFICATION', $num_notif);
        $req->bindValue(':NUM_UTILISATEUR', $num_user);
        $req->bindValue(':NUM_PENSEE', $num_pensee);
        $req->bindValue(':NUM_COMMENTAIRE', $commentaire_fils);
        $req->bindValue(':NUM_CONTRIBUTION', $num_contribution);
        $req->bindValue(':CODE_AUSSI', $code_aussi);
        try {
            $req->execute();
            } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }

    }

    private function getUsersAussiVotesPensee($idRef, $idUser, $numRefName, $code_notif){
        $db = \F3il\Application::getDB();

        $code_notif2 = ($numRefName=="NUM_PENSEE") ? "OR CODE_NOTIF=5" : "";

        $sql="SELECT notification.NUM_UTILISATEUR
              FROM notification
              INNER JOIN notifier on notification.NUM_NOTIFICATION = notifier.NUM_NOTIFICATION
              WHERE notification.$numRefName=:$numRefName
              AND CODE_NOTIF =:CODE_NOTIF $code_notif2
              AND notification.NUM_UTILISATEUR !=:NUM_UTILISATEUR";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(":$numRefName",$idRef);
            $req->bindValue(':NUM_UTILISATEUR',$idUser);
            $req->bindValue(':CODE_NOTIF',$code_notif);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getUsersAussiContribuesPensee($num_pensee, $num_user, $code_notif){

        return $this->getUsersAussiVotesPensee($num_pensee, $num_user, "NUM_PENSEE", $code_notif);
    }

    private function getUsersAussiCommentesContribution($num_contribution, $num_user){
        $db = \F3il\Application::getDB();
        $sql="SELECT DISTINCT notification.NUM_UTILISATEUR
              FROM notification
              INNER JOIN notifier ON notification.NUM_NOTIFICATION = notifier.NUM_NOTIFICATION
              WHERE notification.NUM_CONTRIBUTION=:NUM_CONTRIBUTION
              AND CODE_NOTIF IN (221, 321)
              AND notification.NUM_UTILISATEUR !=:NUM_UTILISATEUR";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(":NUM_CONTRIBUTION",$num_contribution);
            $req->bindValue(':NUM_UTILISATEUR',$num_user);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function insertIntoNotifier($idUserNotifFrom, $idNotifFrom, $idPensee, $idContribution, $idCcommentaire, $code_notifier)
    {
        $db = \F3il\Application::getDB();
        $sql="INSERT INTO notifier SET"
            ." NUM_UTILISATEUR=:NUM_UTILISATEUR"
            .",NUM_NOTIFICATION=:NUM_NOTIFICATION"
            .",LU=:LU"
            .",NUM_PENSEE=:NUM_PENSEE"
            .",NUM_CONTRIBUTION=:NUM_CONTRIBUTION"
            .",NUM_COMMENTAIRE=:NUM_COMMENTAIRE"
            .",CODE_NOTIF=:CODE_NOTIF";
        $req = $db->prepare($sql);
        $req->bindValue(':NUM_UTILISATEUR', $idUserNotifFrom);
        $req->bindValue(':NUM_NOTIFICATION', $idNotifFrom);
        $req->bindValue(':LU', 0);
        $req->bindValue(':NUM_PENSEE', $idPensee);
        $req->bindValue(':NUM_CONTRIBUTION', $idContribution);
        $req->bindValue(':NUM_COMMENTAIRE', $idCcommentaire);
        $req->bindValue(':CODE_NOTIF', $code_notifier);
        try {
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }

    }

    private function insertNotification(array $live_notifs_params){
        $db = \F3il\Application::getDB();

        $vote = (isset($live_notifs_params["VOTE"]))?$live_notifs_params["VOTE"]:0;

        $sql = "INSERT INTO notification SET "
            . " NUM_CONTRIBUTION=:NUM_CONTRIBUTION"
            . ",NUM_UTILISATEUR=:NUM_UTILISATEUR"
            . ",NUM_PAGE=:NUM_PAGE"
            . ",NUM_COMMENTAIRE=:NUM_COMMENTAIRE"
            . ",NUM_PENSEE=:NUM_PENSEE"
            . ",VOTE=:VOTE"
            . ",NUM_VOTE=:NUM_VOTE";
        $req = $db->prepare($sql);
        $req->bindValue(':NUM_CONTRIBUTION', $live_notifs_params["NUM_CONTRIBUTION"]);
        $req->bindValue(':NUM_UTILISATEUR', $live_notifs_params["NUM_UTILISATEUR"]);
        $req->bindValue(':NUM_PAGE', $live_notifs_params["NUM_PAGE"]);
        $req->bindValue(':NUM_COMMENTAIRE', $live_notifs_params["NUM_COMMENTAIRE"]);
        $req->bindValue(':NUM_PENSEE', $live_notifs_params["NUM_PENSEE"]);
        $req->bindValue(':NUM_VOTE', $live_notifs_params["NUM_VOTE"]);
        $req->bindValue(':VOTE', $vote);
        try {
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }
        $sql = "SELECT LAST_INSERT_ID() AS NUM_NOTIFICATION";
        $req = $db->prepare($sql);
        $req->execute();
        $num_notification =  $req->fetch(\PDO::FETCH_ASSOC);
        $num_notification = $num_notification["NUM_NOTIFICATION"];
        return $num_notification;

    }




    public function getUserIDFromRefID($refID, $ref){
        $db = \F3il\Application::getDB();
        $upperRef = strtoupper($ref);
        $sql="SELECT NUM_UTILISATEUR
              FROM $ref
              WHERE NUM_$upperRef=:NUM_$upperRef";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(":NUM_$upperRef",$refID);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $userID =  $req->fetch(\PDO::FETCH_ASSOC);
        $userID = $userID["NUM_UTILISATEUR"];
        return $userID;
    }

    private function isNotifVoteExiste($idVote)
    {
        $db = \F3il\Application::getDB();
        $sql = "select * from notification where NUM_VOTE = :NUM_VOTE AND NUM_VOTE!=0";
        $req = $db->prepare($sql);
        try {
            $req->bindValue(':NUM_VOTE', $idVote);
            $req->execute();
        } catch (Exception $ex) {
            throw new \F3il\Error("Erreur SQL" . $ex->getMessage());
        }

        if($req->rowCount()==0)
        {
            return false;
        }else{
            return true;
        }

    }

    private function deleteNotifVote($idVote)
    {
        $db = \F3il\Application::getDB();
        $req=$db->prepare("delete from notification where NUM_VOTE=:NUM_VOTE");
        $req->bindValue(':NUM_VOTE',$idVote);
        $req->execute();
    }


    //Fin Gestion







    public function posterCommentaire(array $data, $depth, $idUserOnline, $num_page)
    {

        $db = \F3il\Application::getDB();
        $parentCommentaire = 0;
        if($depth<=3) {
            $sql = "INSERT INTO commentaire SET "
                . " NUM_CONTRIBUTION = :NUM_CONTRIBUTION"
                . ",NUM_UTILISATEUR=:NUM_UTILISATEUR"
                . ",LIBELLE_COMMENTAIRE=:LIBELLE_COMMENTAIRE"
                . ",PARENT_NUM_COMMENTAIRE=:PARENT_NUM_COMMENTAIRE"
                . ",DEPTH=:DEPTH";
            $req = $db->prepare($sql);
            $req->bindValue(':NUM_CONTRIBUTION', $data['NUM_CONTRIBUTION']->value);
            $req->bindValue(':NUM_UTILISATEUR', $data['NUM_UTILISATEUR']->value);
            $req->bindValue(':LIBELLE_COMMENTAIRE', $data['LIBELLE_COMMENTAIRE']->value);
            $req->bindValue(':PARENT_NUM_COMMENTAIRE', $data['PARENT_NUM_COMMENTAIRE']->value);
            $req->bindValue(':DEPTH', $depth);
            $parentCommentaire = $data['PARENT_NUM_COMMENTAIRE']->value;
        }else{
            $sql="select PARENT_NUM_COMMENTAIRE,DEPTH from commentaire where num_commentaire =:num_commentaire";
            $req = $db->prepare($sql);
            $req->bindValue(':num_commentaire', $data['PARENT_NUM_COMMENTAIRE']->value);
            $req->execute();
            $res=$req->fetch(\PDO::FETCH_OBJ);
            $sql = "INSERT INTO commentaire SET "
                . " NUM_CONTRIBUTION = :NUM_CONTRIBUTION"
                . ",NUM_UTILISATEUR=:NUM_UTILISATEUR"
                . ",LIBELLE_COMMENTAIRE=:LIBELLE_COMMENTAIRE"
                . ",PARENT_NUM_COMMENTAIRE=:PARENT_NUM_COMMENTAIRE"
                . ",DEPTH=:DEPTH";
            $req = $db->prepare($sql);
            $req->bindValue(':NUM_CONTRIBUTION', $data['NUM_CONTRIBUTION']->value);
            $req->bindValue(':NUM_UTILISATEUR', $data['NUM_UTILISATEUR']->value);
            $req->bindValue(':LIBELLE_COMMENTAIRE', $data['LIBELLE_COMMENTAIRE']->value);
            $req->bindValue(':PARENT_NUM_COMMENTAIRE', $res->PARENT_NUM_COMMENTAIRE);
            $req->bindValue(':DEPTH', $res->DEPTH);

        }
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $sql = "SELECT LAST_INSERT_ID() AS NUM_COMMENTAIRE";
        $req = $db->prepare($sql);
        $req->execute();
        $idLiveCommentaire = $req->fetch(\PDO::FETCH_ASSOC);
        $idLiveCommentaire = $idLiveCommentaire["NUM_COMMENTAIRE"];
        $num_pensee_commentaire = $this->getPenseeFromCommentaire($idLiveCommentaire);
        $idLiveContribution = $this->getContributionFromCommentaire($idLiveCommentaire);
        $live_notifs_params = array(
            "NUM_CONTRIBUTION"=>$idLiveContribution,
            "NUM_UTILISATEUR" =>$idUserOnline,
            "NUM_PAGE"=>$num_page,
            "NUM_COMMENTAIRE"=>$parentCommentaire,
            "NUM_PENSEE" =>$num_pensee_commentaire,
            "NUM_VOTE"=>0,
            "NUM_COMMENTAIRE_FILS"=>$idLiveCommentaire,
        );

        $this->sendNotification($live_notifs_params);
        return $idLiveCommentaire;
    }


    private function getContributionFromCommentaire($commentaireID){
        $db = \F3il\Application::getDB();
        $sql="SELECT NUM_CONTRIBUTION FROM commentaire WHERE NUM_COMMENTAIRE=:NUM_COMMENTAIRE";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_COMMENTAIRE',$commentaireID);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $data =  $req->fetch(\PDO::FETCH_ASSOC);
        return $data["NUM_CONTRIBUTION"];
    }

    public function getPenseeFromCommentaire($commentaireID){
        $db = \F3il\Application::getDB();
        $sql="SELECT p.NUM_PENSEE
              FROM pensee p
              INNER JOIN contribution con ON p.NUM_PENSEE = con.NUM_PENSEE
              INNER JOIN commentaire com ON con.NUM_CONTRIBUTION = com.NUM_CONTRIBUTION
              WHERE com.NUM_COMMENTAIRE=:NUM_COMMENTAIRE";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_COMMENTAIRE',$commentaireID);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $data =  $req->fetch(\PDO::FETCH_ASSOC);
        return $data["NUM_PENSEE"];
    }

    public function deletecommentaire($num_commentaire)
    {
        //On recupère le commentaire à supprimer

        $db = \F3il\Application::getDB();
        $req=$db->prepare("select * from commentaire where num_commentaire=:num_commentaire");
        $req->bindValue(':num_commentaire',$num_commentaire);
        $req->execute();
        $comment=$req->fetch(\PDO::FETCH_OBJ);

        //On supprime le commentaire
        $req=$db->prepare("delete from commentaire where num_commentaire=:num_commentaire");
        $req->bindValue(':num_commentaire',$num_commentaire);
        $req->execute();

        //On monte tous les enfants
        $req=$db->prepare("update commentaire set PARENT_NUM_COMMENTAIRE=:PARENT_NUM_COMMENTAIRE where PARENT_NUM_COMMENTAIRE=:NUM_COMMENTAIRE");
        $req->bindValue(':PARENT_NUM_COMMENTAIRE',$comment->PARENT_NUM_COMMENTAIRE);
        $req->bindValue(':NUM_COMMENTAIRE',$comment->NUM_COMMENTAIRE);
        $req->execute();

    }

    public function findallwithchildren($num_contibution)
    {
        $db = \F3il\Application::getDB();
        $req=$db->prepare("select * from commentaire where NUM_CONTRIBUTION=:NUM_CONTRIBUTION");
        $req->execute();
        $comments_by_contribution=$req->fetchAll(\PDO::FETCH_OBJ);
        $comment_by_id=[];

        foreach($comments_by_contribution as $comment){
            $comment_by_id[$comment->NUM_COMMENTAIRE]=$comment;
        }



        foreach($comments_by_contribution as $k=>$comment){

            if($comment->PARENT_NUM_COMMENTAIRE!=0)
            {
                $comment_by_id[$comment->PARENT_NUM_COMMENTAIRE]->children[]=$comment;
                unset($comments_by_contribution[$k]);
            }

        }

        return $comments_by_contribution;

    }

    public function deletewithchildren($num_contibution)
    {
        //On recupère le commentaire à supprimer

        $db = \F3il\Application::getDB();
        $req=$db->prepare("select * from commentaire where NUM_CONTRIBUTION=:NUM_CONTRIBUTION");
        $req->bindValue(':NUM_CONTRIBUTION',$num_contibution);
        $req->execute();
        $comment=$req->fetchAll();

        $comments=$this->findallwithchildren($num_contibution);

        //On supprime le commentaire
        $req=$db->prepare("delete from commentaire where num_commentaire=:num_commentaire");
        $req->bindValue(':num_commentaire',$num_contibution);
        $req->execute();

        //On monte tous les enfants
        $req=$db->prepare("update commentaire set PARENT_NUM_COMMENTAIRE=:PARENT_NUM_COMMENTAIRE where PARENT_NUM_COMMENTAIRE=:NUM_COMMENTAIRE");
        $req->bindValue(':PARENT_NUM_COMMENTAIRE',$comment->PARENT_NUM_COMMENTAIRE);
        $req->bindValue(':NUM_COMMENTAIRE',$comment->NUM_COMMENTAIRE);
        $req->execute();

    }

    public function getchildrenIds($comment)
    {
        $ids = [];

        foreach($comment->children as $child){
            $ids[]=$child->id;
            if(isset($child->children)){

            }
        }
        return $ids;
    }

    public function parentexist($parent_id, $depth)
    {
        $db = \F3il\Application::getDB();

        if($parent_id!=0)
        {
            $req=$db->prepare("select NUM_COMMENTAIRE, DEPTH from commentaire where NUM_COMMENTAIRE=:PARENT_NUM_COMMENTAIRE");
            $req->bindValue(':PARENT_NUM_COMMENTAIRE',$parent_id);
            $req->execute();
            $data=$req->fetch(\PDO::FETCH_ASSOC);

            if($data==false)
            {
                throw new \F3il\Error("Ce parent n'existe pas");

            }
            $depth = $data["DEPTH"]+1;
            return $depth;

        }
        return 0;

    }

    public function getPenseeUtilisateur($currentPage, $penseesParPage)
    {
        $db = \F3il\Application::getDB();

        $sql = "SELECT * FROM `pensee`
              natural JOIN `utilisateur`
              natural JOIN `annee_etude`
              WHERE `pensee`.`VALIDER_PENSEE` = 1
              ORDER BY `DATE_PENSEE` DESC LIMIT ".(($currentPage-1)*$penseesParPage).",$penseesParPage";

        $req = $db->prepare($sql);
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getContributionFromPensee($id)
    {
        $db = \F3il\Application::getDB();

        $sql = "SELECT  * "
            . "FROM `pensee`"
            . " inner JOIN `contribution` "
            . " inner join utilisateur "
            . " natural join annee_etude "
            . "WHERE `pensee`.`VALIDER_PENSEE` = 1"
            . " and `pensee`.`NUM_PENSEE`=:id"
            . " and `pensee`.`NUM_PENSEE`=`contribution`.`NUM_PENSEE`"
            . " and `utilisateur`.`NUM_UTILISATEUR`=`contribution`.`NUM_UTILISATEUR`"
            . "ORDER BY `contribution`.`DATE_CONTRIBUTION` DESC";

        $req = $db->prepare($sql);
        try{
            $req->bindValue(':id',$id);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        $data=$req->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    public function getCommentairesFromContribution($num_contibution)
    {

        $db = \F3il\Application::getDB();

        $sql = "SELECT * FROM commentaire WHERE NUM_CONTRIBUTION=:NUM_CONTRIBUTION GROUP BY DATE_COMMENTAIRE DESC";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_CONTRIBUTION',$num_contibution);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        $data=$req->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    public function activerutilisateur($email, $validation_code)
    {
        $db = \F3il\Application::getDB();

        $sql = "UPDATE utilisateur SET ACTIVE=1, VALIDATION_CODE=0 WHERE EMAIL = :EMAIL AND VALIDATION_CODE= :VALIDATION_CODE";

        $req = $db->prepare($sql);
        $req->bindValue(':EMAIL', $email);
        $req->bindValue(':VALIDATION_CODE', $validation_code);

        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

    }


    public function majValidationCode($validation_code,$email)
    {
        $db = \F3il\Application::getDB();

        $sql = "UPDATE utilisateur SET VALIDATION_CODE='$validation_code' WHERE EMAIL = :EMAIL";

        $req = $db->prepare($sql);
        $req->bindValue(':EMAIL', $email);

        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

    }

    public function val_getIdByEmail($validation_code, $email)
    {
        $db = \F3il\Application::getDB();
        $sql = "select num_utilisateur from utilisateur where validation_code = :validation_code AND email = :email ";
        $req = $db->prepare($sql);
        try {
            $req->bindValue(':validation_code', $validation_code);
            $req->bindValue(':email', $email);
            $req->execute();
        } catch (Exception $ex) {
            throw new \F3il\Error("Erreur SQL" . $ex->getMessage());
        }
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function majMotdepasse($mdp, $email,$data)
    {
        $db = \F3il\Application::getDB();

        $sql = "UPDATE utilisateur SET MOTDEPASSE= :MOTDEPASSE, VALIDATION_CODE= 0, creation = :creation WHERE EMAIL = :EMAIL";
        $salt = date('Y-m-d H:i:s');
        $req = $db->prepare($sql);
        $data['password']->value = $mdp;
        $req->bindValue(':EMAIL', $email);
        $req->bindValue(':MOTDEPASSE', \F3il\Authentication::hash($data['password']->value, $salt));
        $req->bindValue(':creation', $salt);
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
    }



    private function recordExists($ref, $num_ref)
    {
        if($ref=='pensee')
        {
            $db = \F3il\Application::getDB();
            $sql = "select * from $ref where NUM_PENSEE = :NUM_PENSEE";
            $req = $db->prepare($sql);
            try {
                $req->bindValue(':NUM_PENSEE', $num_ref);
                $req->execute();
            } catch (Exception $ex) {
                throw new \F3il\Error("Erreur SQL" . $ex->getMessage());
            }
            if($req->rowCount()==0)
            {
                throw new \Exception('Impossible de voter pour une pensée qui n\'existe pas');
            }
        }
        if($ref=='contribution')
        {
            $db = \F3il\Application::getDB();
            $sql = "select * from $ref where NUM_CONTRIBUTION = :NUM_CONTRIBUTION";
            $req = $db->prepare($sql);
            try {
                $req->bindValue(':NUM_CONTRIBUTION', $num_ref);
                $req->execute();
            } catch (Exception $ex) {
                throw new \F3il\Error("Erreur SQL" . $ex->getMessage());
            }
            if($req->rowCount()==0)
            {
                throw new \Exception('Impossible de voter pour une contribution qui n\'existe pas');
            }
        }
        if($ref=='commentaire')
        {
            $db = \F3il\Application::getDB();
            $sql = "select * from $ref where NUM_COMMENTAIRE = :NUM_COMMENTAIRE";
            $req = $db->prepare($sql);
            try {
                $req->bindValue(':NUM_COMMENTAIRE', $num_ref);
                $req->execute();
            } catch (Exception $ex) {
                throw new \F3il\Error("Erreur SQL" . $ex->getMessage());
            }
            if($req->rowCount()==0)
            {
                throw new \Exception('Impossible de voter pour un commentaire qui n\'existe pas');
            }
        }
    }

    public function voteexistant($num_user, $ref, $num_ref)
    {
        $db = \F3il\Application::getDB();
        $sql = "SELECT * from vote where NUM_UTILISATEUR = :NUM_UTILISATEUR and REF=:REF and NUM_REF=:NUM_REF";
        $req = $db->prepare($sql);

        try {
            $req->bindValue(':NUM_UTILISATEUR', $num_user);
            $req->bindValue(':REF', $ref);
            $req->bindValue(':NUM_REF', $num_ref);
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }

        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    private function vote($ref, $num_ref, $num_user, $vote,$num_page)
    {
        $db = \F3il\Application::getDB();
        $this->recordExists($ref, $num_ref);
        $voteexistant=$this->voteexistant($num_user,$ref, $num_ref);

        //echo $voteexistant['VOTE'], $voteexistant['REF'], $voteexistant['NUM_REF'];
        //die("okok");

        if($voteexistant)
        {
            
            if($voteexistant['VOTE']==$vote && $voteexistant['REF']==$ref && $voteexistant['NUM_REF']== $num_ref)
            {
                $num_vote = $voteexistant['NUM_VOTE'];
                $this->sendNotifVote($ref, $num_ref, $num_user, $num_vote ,$num_page, $vote);
                $db->query("delete from vote where num_vote=$num_vote");
                //On met $num_user = -1, pour plus facilement supprimer le vote dans sendNotification()
                //on ajoute la valeur du vote, pour adapter la notification en cas de changement (concerne que la pensée)
                return false;
            }elseif($voteexistant['VOTE']!=$vote && $voteexistant['REF']==$ref && $voteexistant['NUM_REF']== $num_ref) {
                $this->former_vote=$voteexistant;
                $voteexistantID = $voteexistant['NUM_VOTE'];
                $sql = "UPDATE vote SET VOTE= :VOTE, VOTE_DATE = :VOTE_DATE WHERE NUM_VOTE = $voteexistantID";
                $req = $db->prepare($sql);
                $req->bindValue(':VOTE', $vote);
                $req->bindValue(':VOTE_DATE', date('Y-m-d H:i:s'));
                // echo $req->execute();

                try {
                    $req->execute();
                } catch (\PDOException $exc) {
                    throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
                }
                $this->sendNotifVote($ref, $num_ref, $num_user, $voteexistantID ,$num_page, $vote);
                return true;
            }
        }


        $sql = "insert into vote set REF=:REF, NUM_REF= :NUM_REF, NUM_UTILISATEUR= :NUM_UTILISATEUR, VOTE_DATE=:VOTE_DATE, VOTE=$vote";
        $req = $db->prepare($sql);
        $req->bindValue(':REF', $ref);
        $req->bindValue(':NUM_REF', $num_ref);
        $req->bindValue(':NUM_UTILISATEUR', $num_user);
        $req->bindValue(':VOTE_DATE', date('Y-m-d H:i:s'));
        $req->execute();

        //Gestion des notifs
        $sql = "SELECT LAST_INSERT_ID() AS NUM_REF";
        $req = $db->prepare($sql);
        $req->execute();
        $num_vote = $req->fetch(\PDO::FETCH_ASSOC);
        $num_vote = $num_vote["NUM_REF"];
        $this->sendNotifVote($ref, $num_ref, $num_user, $num_vote ,$num_page, $vote);

        return true;
    }

    private function sendNotifVote($ref, $num_ref, $num_user, $num_vote ,$num_page, $vote) //$num_vote ≠ $vote
    {
        switch ($ref) {
            case "pensee":
                $live_notifs_params = array(
                    "NUM_CONTRIBUTION"=>0,
                    "NUM_UTILISATEUR" =>$num_user,
                    "NUM_PAGE"=>$num_page,
                    "NUM_COMMENTAIRE"=>0,
                    "NUM_PENSEE" =>$num_ref,
                    "NUM_VOTE"=>$num_vote,
                    "VOTE"=>$vote,
                );
                $this->sendNotification($live_notifs_params);
                break;
            case "contribution":
                $num_pensee = $this->getPenseeFromContribution($num_ref);
                $num_pensee=$num_pensee["NUM_PENSEE"];
                $live_notifs_params = array(
                    "NUM_CONTRIBUTION"=>$num_ref,
                    "NUM_UTILISATEUR" =>$num_user,
                    "NUM_PAGE"=>$num_page,
                    "NUM_COMMENTAIRE"=>0,
                    "NUM_PENSEE" =>$num_pensee,
                    "NUM_VOTE"=>$num_vote,
                    "VOTE"=>0,
                );
                $this->sendNotification($live_notifs_params);
                break;
            case "commentaire":
                $num_contribution = $this->getContributionFromCommentaire($num_ref);
                $num_pensee = $this->getPenseeFromContribution($num_contribution);
                $num_pensee=$num_pensee["NUM_PENSEE"];
                $live_notifs_params = array(
                    "NUM_CONTRIBUTION"=>$num_contribution,
                    "NUM_UTILISATEUR" =>$num_user,
                    "NUM_PAGE"=>$num_page,
                    "NUM_COMMENTAIRE"=>$num_ref,
                    "NUM_PENSEE" =>$num_pensee,
                    "NUM_VOTE"=>$num_vote,
                    "VOTE"=>0,
                );
                $this->sendNotification($live_notifs_params);
                break;
            default:
                echo "Bad ref";
        }
    }

    public function like($ref, $num_ref, $num_user,$num_page)
    {   $db = \F3il\Application::getDB();
        if($this->vote($ref, $num_ref, $num_user, 1, $num_page))
        {
            $sql_part="";

            if($this->former_vote)
            {
                $sql_part= ", NOMBRE_DISLIKE_PENSEE=NOMBRE_DISLIKE_PENSEE-1";
            }
            $sql = "UPDATE $ref SET NOMBRE_LIKE_PENSEE=NOMBRE_LIKE_PENSEE+1 $sql_part WHERE NUM_PENSEE = $num_ref";
            $db->query($sql);
            return true;
        }else{
            $sql = "UPDATE $ref SET NOMBRE_LIKE_PENSEE=NOMBRE_LIKE_PENSEE-1  WHERE NUM_PENSEE = $num_ref";
            $db->query($sql);
        }
        return false;
    }

    public function dislike($ref, $num_ref, $num_user, $num_page)
    {
        $db = \F3il\Application::getDB();
        if($this->vote($ref, $num_ref, $num_user, -1, $num_page))
        {
            $sql_part="";

            if($this->former_vote)
            {
                $sql_part= ", NOMBRE_LIKE_PENSEE=NOMBRE_LIKE_PENSEE-1";
            }

            $sql = "UPDATE $ref SET NOMBRE_DISLIKE_PENSEE=NOMBRE_DISLIKE_PENSEE+1 $sql_part WHERE NUM_PENSEE = $num_ref";
            $db->query($sql);
            return true;
        }else{
            $sql = "UPDATE $ref SET NOMBRE_DISLIKE_PENSEE=NOMBRE_DISLIKE_PENSEE-1  WHERE NUM_PENSEE = $num_ref";
            $db->query($sql);
        }
        return false;
    }

    public function checkcontribution($ref, $num_ref, $num_user, $num_page)
    {
        $db = \F3il\Application::getDB();
        if($this->vote($ref, $num_ref, $num_user, 1, $num_page))
        {
            $sql_part="";

            if($this->former_vote)
            {

                $sql_part= ", NOMBRE_LIKE_CONTRIBUTION=NOMBRE_LIKE_CONTRIBUTION-1";
            }
            $sql = "UPDATE $ref SET NOMBRE_LIKE_CONTRIBUTION=NOMBRE_LIKE_CONTRIBUTION+1 $sql_part WHERE NUM_CONTRIBUTION = $num_ref";
            $req = $db->query($sql);
            return true;
        }else{
            $sql = "UPDATE $ref SET NOMBRE_LIKE_CONTRIBUTION=NOMBRE_LIKE_CONTRIBUTION-1  WHERE NUM_CONTRIBUTION = $num_ref";
            $req = $db->query($sql);
        }
        return false;
    }

    public function checkcommentaire($ref, $num_ref, $num_user, $num_page)
    {
        $db = \F3il\Application::getDB();
        if($this->vote($ref, $num_ref, $num_user, 1, $num_page))
        {
            $sql_part="";

            if($this->former_vote)
            {

                $sql_part= ", NOMBRE_LIKE_COMMENTAIRE=NOMBRE_LIKE_COMMENTAIRE-1";
            }
            $sql = "UPDATE $ref SET NOMBRE_LIKE_COMMENTAIRE=NOMBRE_LIKE_COMMENTAIRE+1 $sql_part WHERE NUM_COMMENTAIRE = $num_ref";
            $req = $db->query($sql);
            return true;
        }else{
            $sql = "UPDATE $ref SET NOMBRE_LIKE_COMMENTAIRE=NOMBRE_LIKE_COMMENTAIRE-1  WHERE NUM_COMMENTAIRE = $num_ref";
            $req = $db->query($sql);
        }
        return false;
    }





    /**
     * permet d'ajouter une classe is-liked ou is-disliked suivant un enregistrement
     * @param $num_user
     * @return mixed
     * @throws \F3il\Error
     */
    public function getClassVote($num_user, $num_ref, $ref)
    {
        $db = \F3il\Application::getDB();
        $sql = "SELECT * from vote where NUM_UTILISATEUR = :NUM_UTILISATEUR and NUM_REF=:NUM_REF and REF=:REF";
        $req = $db->prepare($sql);

        try {
            $req->bindValue(':NUM_UTILISATEUR', $num_user);
            $req->bindValue(':NUM_REF', $num_ref);
            $req->bindValue(':REF', $ref);
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }

        $data=$req->fetch(\PDO::FETCH_ASSOC);
        if($data['VOTE'])
        {
            return $data['VOTE']==1 ? 'is-liked' : 'is-disliked';
        }else{
            return '';
        }

    }

    public function updateCount($ref,$num_ref)
    {
        $db = \F3il\Application::getDB();
        $sql = "SELECT count(NUM_VOTE) as count, VOTE from vote where REF= :REF and NUM_REF= :NUM_REF group by VOTE";
        $req = $db->prepare($sql);
        try {
            $req->bindValue(':REF', $ref);
            $req->bindValue(':NUM_REF', $num_ref);
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }
        $votes=$req->fetchAll(\PDO::FETCH_ASSOC);

        // die();
        $counts = [
            '-1'=>0,
            '1'=>0
        ];
        //die();
        //var_dump($votes);
        foreach($votes as $vote)
        {
            $counts[$vote['VOTE']] = $vote['count'];
        }

        $sql = "UPDATE $ref SET NOMBRE_LIKE_PENSEE= {$counts[1]}, NOMBRE_DISLIKE_PENSEE = {$counts[-1]} WHERE NUM_PENSEE = $num_ref";
        $req = $db->query($sql);
        return true;
    }

    public function getLikePensee($ref, $num_ref)
    {
        $db = \F3il\Application::getDB();
        $sql="select NOMBRE_LIKE_PENSEE, NOMBRE_DISLIKE_PENSEE from $ref where NUM_PENSEE=:NUM_PENSEE";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_PENSEE',$num_ref);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function getValeurVoteRef($num_ref, $ref, $num_utilisateur)
    {
        $db = \F3il\Application::getDB();
        $sql="SELECT vote FROM vote WHERE REF=:REF AND NUM_REF=:NUM_REF AND NUM_UTILISATEUR=:NUM_UTILISATEUR";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':REF',$ref);
            $req->bindValue(':NUM_REF',$num_ref);
            $req->bindValue(':NUM_UTILISATEUR',$num_utilisateur);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        $data= $req->fetch(\PDO::FETCH_ASSOC);
        return $data["vote"];
    }

    public function getcheckcontribution($ref, $num_ref)
    {
        $db = \F3il\Application::getDB();
        $sql="select NOMBRE_LIKE_CONTRIBUTION   from $ref where NUM_CONTRIBUTION=:NUM_CONTRIBUTION";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_CONTRIBUTION',$num_ref);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function getchecommentaire($ref, $num_ref)
    {
        $db = \F3il\Application::getDB();
        $sql="select NOMBRE_LIKE_COMMENTAIRE from $ref where NUM_COMMENTAIRE=:NUM_COMMENTAIRE";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_COMMENTAIRE',$num_ref);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function getnombrecommentaire($num_contribution)
    {
        $db = \F3il\Application::getDB();
        $sql="SELECT COUNT(NUM_COMMENTAIRE) AS NBRE_COMMENTAIRE FROM commentaire WHERE NUM_CONTRIBUTION=:NUM_CONTRIBUTION";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_CONTRIBUTION',$num_contribution);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function contributionexist($num_utilisateur, $num_pensee)
    {
        $db = \F3il\Application::getDB();
        $sql="SELECT COUNT(NUM_CONTRIBUTION) AS NBRE_CONTRIBUTION FROM contribution  WHERE NUM_UTILISATEUR=:NUM_UTILISATEUR AND NUM_PENSEE=:NUM_PENSEE";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_UTILISATEUR',$num_utilisateur);
            $req->bindValue(':NUM_PENSEE',$num_pensee);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $exist= $req->fetch(\PDO::FETCH_ASSOC);

        if($exist['NBRE_CONTRIBUTION']>0) return true;
        return false;

    }


    //Recuperer le nombre de pensee
    public function getNombrePensee(){

        $db = \F3il\Application::getDB();
        $sql="SELECT COUNT(NUM_PENSEE) AS NBRE_PENSEE FROM pensee";
        $req = $db->prepare($sql);
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur de récuperation nombre pensée" . $exc->getMessage());
        }
        return $req->fetch(\PDO::FETCH_ASSOC);


    }

    //Table pour tester la fonction de recupération mysqli_insert_id();

    public function selecTest( $numRefName, $idRef, $idUser)
    {

        $db = \F3il\Application::getDB();
        $sql="SELECT TEST_CONTENT
              FROM test
              WHERE $numRefName=:$numRefName
              AND numero !=:numero";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(":$numRefName",$idRef);
            $req->bindValue(':numero',$idUser);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Obtenir les notifications de l'utilisateur connecté
     * @param $id
     */
    public function getNotififationsFromNotifier($userID)
    {
        $db = \F3il\Application::getDB();
        $sql="SELECT *
              FROM notifier
              WHERE NUM_UTILISATEUR =:NUM_UTILISATEUR";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_UTILISATEUR',$userID);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur de récuperation nombre pensée" . $exc->getMessage());
        }
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }



    public function getNotififationsFromAussi($userID)
    {
        $db = \F3il\Application::getDB();
        $sql="SELECT *
              FROM aussi
              WHERE NUM_UTILISATEUR =:NUM_UTILISATEUR";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_UTILISATEUR',$userID);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur de récuperation nombre pensée" . $exc->getMessage());
        }
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNombreNotifByCode($num_user, $code_notif, $num_ref, $num_ref_name, $refNotif, $code_name){
        $db = \F3il\Application::getDB();

        $sql="SELECT
              COUNT($code_name) AS NBRE_CODE
              FROM $refNotif
              WHERE NUM_UTILISATEUR = :NUM_UTILISATEUR
               AND $code_name=:$code_name
               AND $num_ref_name=:$num_ref_name";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_UTILISATEUR',$num_user);
            $req->bindValue(":$code_name",$code_notif);
            $req->bindValue(":$num_ref_name",$num_ref);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $nombre =  $req->fetch(\PDO::FETCH_ASSOC);
        return $nombre["NBRE_CODE"];
    }


    public function getNotifAutorInfo($num_notification){
        $db = \F3il\Application::getDB();
        $sql="SELECT * FROM notification WHERE NUM_NOTIFICATION =:NUM_NOTIFICATION";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_NOTIFICATION',$num_notification);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        return  $req->fetch(\PDO::FETCH_ASSOC);

    }

    public function majVuNotif($num_utilisateur, $date_notif){

        $this->majVuNotifBoth($num_utilisateur,$date_notif, "notifier");
        $this->majVuNotifBoth($num_utilisateur,$date_notif, "aussi");

    }

    public function getDateNotif($num_utilisateur, $num_pensee, $num_contribution, $num_commentaire, $num_code)
    {
        $array = array(4, 44, 444);

        $ref = (!in_array($num_code, $array))?"notifier":"aussi";
        $code_ref_name = (!in_array($num_code, $array))?"CODE_NOTIF":"CODE_AUSSI";
        $date_ref_name = (!in_array($num_code, $array))?"DATE_NOTIFIER":"DATE_AUSSI";

        $db = \F3il\Application::getDB();
        $sql="SELECT $date_ref_name FROM $ref
             	WHERE NUM_UTILISATEUR=:NUM_UTILISATEUR
                AND NUM_PENSEE =:NUM_PENSEE
                AND NUM_CONTRIBUTION=:NUM_CONTRIBUTION
                AND NUM_COMMENTAIRE=:NUM_COMMENTAIRE
                AND $code_ref_name=:$code_ref_name
              ORDER BY $date_ref_name DESC
              LIMIT  1";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_PENSEE', $num_pensee);
            $req->bindValue(':NUM_UTILISATEUR', $num_utilisateur);
            $req->bindValue(":$code_ref_name", $num_code);
            $req->bindValue(':NUM_CONTRIBUTION', $num_contribution);
            $req->bindValue(':NUM_COMMENTAIRE', $num_commentaire);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $date= $req->fetch(\PDO::FETCH_ASSOC);
        return $date["$date_ref_name"];
    }


    private function majVuNotifBoth($num_utilisateur, $date_notif, $ref){
        $db = \F3il\Application::getDB();

        $date_ref_name = ($ref=="notifier")?"DATE_NOTIFIER":"DATE_AUSSI";

        $sql = "UPDATE $ref SET VU=:VU
                WHERE NUM_UTILISATEUR=:NUM_UTILISATEUR
                AND $date_ref_name<= :$date_ref_name";
        $req = $db->prepare($sql);
        $req->bindValue(':VU', 1);
        $req->bindValue(":$date_ref_name", $date_notif);
        $req->bindValue(':NUM_UTILISATEUR', $num_utilisateur);
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
    }




    public function majLuNotif($num_utilisateur, $num_pensee, $num_contribution, $num_commentaire, $num_code){
        $db = \F3il\Application::getDB();

        $array = array(4, 44, 444);

        $ref = (!in_array($num_code, $array))?"notifier":"aussi";
        $code_ref_name = (!in_array($num_code, $array))?"CODE_NOTIF":"CODE_AUSSI";

        $sql = "UPDATE $ref SET LU=:LU
                WHERE NUM_UTILISATEUR=:NUM_UTILISATEUR
                AND NUM_PENSEE = :NUM_PENSEE
                AND $code_ref_name=:$code_ref_name
                AND NUM_CONTRIBUTION=:NUM_CONTRIBUTION
                AND NUM_COMMENTAIRE=:NUM_COMMENTAIRE";
        $req = $db->prepare($sql);
        $req->bindValue(':LU', 1);
        $req->bindValue(':NUM_PENSEE', $num_pensee);
        $req->bindValue(':NUM_UTILISATEUR', $num_utilisateur);
        $req->bindValue(":$code_ref_name", $num_code);
        $req->bindValue(':NUM_CONTRIBUTION', $num_contribution);
        $req->bindValue(':NUM_COMMENTAIRE', $num_commentaire);
        try{
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }
    }

    public function getNotifsRowItems($num_user, $code_notif, $row, $num_ref,$num_ref_name,
                                      $refNotif, $code_name, $num_user_name, $date_name){
        $db = \F3il\Application::getDB();
        $sql="SELECT *
              FROM $refNotif
              WHERE $num_user_name = :$num_user_name
                AND $code_name=:$code_name
                AND $num_ref_name=:$num_ref_name
              ORDER BY $date_name DESC
              LIMIT $row, 1";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(":$num_user_name",$num_user);
            $req->bindValue(":$code_name",$code_notif);
            $req->bindValue(":$num_ref_name",$num_ref);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        return $req->fetch(\PDO::FETCH_ASSOC);

    }

    public function getAnneeEtudeFromUser($userID){
        $db = \F3il\Application::getDB();
        $sql="SELECT LIBELLE_ANNEE_ETUDE
            FROM annee_etude INNER JOIN utilisateur
            ON annee_etude.NUM_ANNEE_ETUDE = utilisateur.NUM_ANNEE_ETUDE
            WHERE NUM_UTILISATEUR =:NUM_UTILISATEUR";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_UTILISATEUR',$userID);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $anneeEtude =  $req->fetch(\PDO::FETCH_ASSOC);
        return $anneeEtude["LIBELLE_ANNEE_ETUDE"];
    }

    public function getPenseeFromContribution($contributionID){
        $db = \F3il\Application::getDB();
        $sql="SELECT pensee.NUM_PENSEE, TITRE_PENSEE
              FROM pensee INNER JOIN contribution
              ON pensee.NUM_PENSEE = contribution.NUM_PENSEE
              WHERE NUM_CONTRIBUTION=:NUM_CONTRIBUTION";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(':NUM_CONTRIBUTION',$contributionID);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $pensee =  $req->fetch(\PDO::FETCH_ASSOC);
        return $pensee;
    }

    public function getTitreRef($num_ref, $ref, $num_ref_name){
        $upperRef = strtoupper($ref);
        $head = ($ref!="pensee") ? "LIBELLE" : "TITRE";
        $headTitle = $head."_$upperRef";
        $db = \F3il\Application::getDB();
        $sql="SELECT $headTitle
              FROM $ref
              WHERE $num_ref_name=:$num_ref_name";
        $req = $db->prepare($sql);
        try{
            $req->bindValue(":$num_ref_name",$num_ref);
            $req->execute();
        }catch (\PDOException $exc){
            throw new \F3il\Error("Erreur SQL" . $exc->getMessage());
        }

        $pensee =  $req->fetch(\PDO::FETCH_ASSOC);
        return $pensee["$headTitle"];
    }



}





