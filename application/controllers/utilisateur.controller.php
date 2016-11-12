<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

class UtilisateurController extends \F3il\Controller
{


    public function __construct()
    {
        $this->setDefaultActionName('posterpensee');

    }

    public function validerpenseeAction()
    {
        $page = \F3il\Page::getInstance();
        $page->setTemplate('header');
        $page->setView('admin');

        $adminmodel = new AdminModel();
        $page->penseesinvalidees = $adminmodel->getPenseeAdmin();

        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $adminmodel->validerPensee($_POST['num_pensee']);
        }

        if (!\F3il\Authentication::isAuthenticated()) {
            \F3il\HttpHelper::redirect('?controller=index&action=index');
        }
    }

    private function txtNotfication($isLu, $num_pensee, $num_contribution, $num_page, $num_commentaire, $code_notif, $notifSymbol, $txtcontent, $dateNotif){

        $dateNotifFormatee = date("d-m-Y  H:i", strtotime($dateNotif));
        $notifSymboll = ($code_notif==3)?strtolower($notifSymbol)."r":strtolower($notifSymbol);
        return "<li class=\"$isLu  notif\" data-pensee=\"$num_pensee\" data-contribution=\"$num_contribution\" data-commentaire=\"$num_commentaire\" data-code=\"$code_notif\">
                <a href=\"http://localhost/projet_pensonslibre/?controller=utilisateur&action=posterpensee&p=$num_page\" class=\"notif-link\">
                                        <div class=\"notif-row\">
                                        <div class=\"notif-symbol$notifSymboll\">$notifSymbol</div>
                                        <div class=\"notif-content\">$txtcontent<br/><span class=\"notif-date\">$dateNotifFormatee</span></div>
                                        </div>
                                    </a></li>";
    }

    private $notifs_li = array();
    private $nombre_notif = 0;

    private function getNotificationRef( $notif_notifier,$num_ref_name,$ref, $num_user, $code_notif,
                                            $symbol, $partNotifTest, $partNotifTests,$code_name, $num_user_name, $date_name){
        //Vérifier ceci au runtime

        $utilisateurModel = new utilisateurModel();
        $refNotif = ($code_name=="CODE_NOTIF")?"notifier":"aussi";

        $nombreNotifByCode = $utilisateurModel->getNombreNotifByCode($num_user, $code_notif, $notif_notifier["$num_ref_name"], $num_ref_name, $refNotif, $code_name );
        $rowItem1 = $utilisateurModel->getNotifsRowItems($num_user, $code_notif, 0, $notif_notifier["$num_ref_name"],
                        $num_ref_name, $refNotif, $code_name, $num_user_name, $date_name);

        $this->nombre_notif+=!$rowItem1["VU"];

        $isLu = (!$rowItem1["LU"]) ? "notifications-li" : "";
        $num_pensee =  $rowItem1["NUM_PENSEE"];
        $num_contribution =$rowItem1["NUM_CONTRIBUTION"];
        $notifAutor = $utilisateurModel->getNotifAutorInfo($rowItem1["NUM_NOTIFICATION"]);
        $num_page = $notifAutor["NUM_PAGE"];

 /*       if($rowItem1["NUM_NOTIFICATION"]==595 && isset($rowItem1["CODE_AUSSI"])==444 && $rowItem1["CODE_AUSSI"]==444){
            print_r($rowItem1);
        }

        if($code_name!="CODE_NOTIF") $rowItem1["NUM_COMMENTAIRE"] = 0;*/
        $num_commentaire = $rowItem1["NUM_COMMENTAIRE"];
        $code_notif = $rowItem1["$code_name"];
        $notifSymbol = $symbol;
        $userIDAutorNotif= $notifAutor["NUM_UTILISATEUR"];
        $anneeEtude = $utilisateurModel->getAnneeEtudeFromUser($userIDAutorNotif);
        $titreRef = $utilisateurModel->getTitreRef($notif_notifier["$num_ref_name"],$ref, $num_ref_name );
        $titreRef= strip_tags($titreRef);
        $txtlength = strlen($titreRef);
        $substrTitre = substr($titreRef, 0, 100);
        $titreRef = ($txtlength>100) ? "$substrTitre..." : $titreRef;
        $userPseudo = "user".($userIDAutorNotif+421);


        $dateNotif = $rowItem1["$date_name"];
        $txtcontent="";
        if($nombreNotifByCode==1){
            $txtcontent = "<b>$userPseudo</b>  ($anneeEtude) $partNotifTest : <b>$titreRef</b> ";
        }elseif($nombreNotifByCode>1){
            $rowItem2 = $utilisateurModel->getNotifsRowItems($_POST["num_utilisateur"], $code_notif, 1, $notif_notifier["$num_ref_name"],
                                            $num_ref_name, $refNotif, $code_name, $num_user_name, $date_name);
            $notifAutor2 = $utilisateurModel->getNotifAutorInfo($rowItem2["NUM_NOTIFICATION"]);
            $userIDAutorNotif2= $notifAutor2["NUM_UTILISATEUR"];
            $anneeEtude2 = $utilisateurModel->getAnneeEtudeFromUser($userIDAutorNotif2);
            $userPseudo2 = "user".($userIDAutorNotif2+421);
            if($nombreNotifByCode==2){
                $txtcontent = "<b>$userPseudo</b> ($anneeEtude) et <b>$userPseudo2</b> ($anneeEtude2) $partNotifTests : <b>$titreRef</b> ";
            }else{
                $rowItem3 = $utilisateurModel->getNotifsRowItems($_POST["num_utilisateur"], $code_notif, 2, $notif_notifier["$num_ref_name"],
                                $num_ref_name, $refNotif, $code_name, $num_user_name, $date_name);
                $notifAutor3   = $utilisateurModel->getNotifAutorInfo($rowItem3["NUM_NOTIFICATION"]);
                $userIDAutorNotif3= $notifAutor3["NUM_UTILISATEUR"];
                $anneeEtude3 = $utilisateurModel->getAnneeEtudeFromUser($userIDAutorNotif3);
                $userPseudo3 = "user".($userIDAutorNotif3+421);
                if($nombreNotifByCode==3){
                    $txtcontent = "<b>$userPseudo</b> ($anneeEtude), <b>$userPseudo2</b> ($anneeEtude2) et <b>$userPseudo3</b> ($anneeEtude3)</b> $partNotifTests : <b>$titreRef</b> ";
                }else{
                    $autrespersonnes = $nombreNotifByCode-2;
                    $txtcontent = "<b>$userPseudo</b> ($anneeEtude), <b>$userPseudo2</b> ($anneeEtude2) et <b>$autrespersonnes autres personnes</b> $partNotifTests : <b>$titreRef</b> ";
                }
            }
        }



        $txtNotfication = $this->txtNotfication($isLu, $num_pensee, $num_contribution,
            $num_page, $num_commentaire, $code_notif,
            $notifSymbol, $txtcontent, $dateNotif);
        $dateNotif=date("d-m-Y  H:i:s", strtotime($dateNotif));
        if(array_key_exists($dateNotif, $this->notifs_li) && $this->notifs_li[$dateNotif] !=$txtNotfication){
            $dateNotif=date("d-m-Y  H:i:s", strtotime($dateNotif)+1);
        }

        if(sizeof($this->notifs_li)>100) return;
        $this->notifs_li[$dateNotif] =$txtNotfication;
        //($num_pensee==89 && $code_notif==221) ? "<h5>".print_r($this->notifs_li)."</h5>" : "";
    }

    public function posterpenseeAction()
    {
        if(isset($_GET["facebook"])){
            $_SESSION['facebook']='nice';
            \F3il\HttpHelper::redirect('?controller=index&action=index&facebook=true');
            exit();
        }

        if(isset($_GET["facebook"])){

        }

        $page = \F3il\Page::getInstance();
        $page->setTemplate('header');
        $page->setView('utilisateur');
        $token = 1;
        setcookie('email', $token, time() + 31536000 * 3);
        $page->form_poster = new PosterForm('?controller=utilisateur&action=posterpensee', \F3il\Form::CREATE_MODE);
        $page->form_contribuer = new ContribuerForm('?controller=utilisateur&action=contribuer', \F3il\Form::CREATE_MODE);
        $page->form_commentaire = new CommentaireForm('?controller=utilisateur&action=commentaire', \F3il\Form::CREATE_MODE);
        $form_poster = $page->form_poster;
        $nombre=0;
        $utilisateurModel = new utilisateurModel();

        //Gestion des notifications
        $user = \F3il\Authentication::getUserData();
        $userID=$user['NUM_UTILISATEUR'];



        if(isset($_POST["num_utilisateur"]) && isset($_POST["notification"]))
        {
                $notifs_notifier = $utilisateurModel->getNotififationsFromNotifier($_POST["num_utilisateur"]);
                $notifs_aussi = $utilisateurModel->getNotififationsFromAussi($_POST["num_utilisateur"]);

                $n1=0; $n11=0; $n2=0; $n22=0; $n221=0; $n3=0; $n33=0; $n321=0; $n5=0; $n4=0;$n44=0;$n444=0;

                $data="";

            if(!empty($notifs_notifier)) {

                foreach ($notifs_notifier as $n => $notif_notifier) {

                    switch($notif_notifier["CODE_NOTIF"]){
                        case 1:
                            if($n1==$notif_notifier["NUM_PENSEE"]) goto jump;


                            $this->getNotificationRef($notif_notifier,"NUM_PENSEE","pensee", $_POST["num_utilisateur"],
                                                            $notif_notifier["CODE_NOTIF"],"C","a apporté une contribution à ta pensée",
                                                                "ont contribué à ta pensée",
                                                                "CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");
                            $n1=$notif_notifier["NUM_PENSEE"];

                            break;
                        case 11 :
                            if($n11==$notif_notifier["NUM_PENSEE"]) goto jump;

                            $this->getNotificationRef($notif_notifier, "NUM_PENSEE","pensee", $_POST["num_utilisateur"],
                                $notif_notifier["CODE_NOTIF"],"V","a voté ta pensée",
                                "ont voté ta pensée", "CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");
                            $n11=$notif_notifier["NUM_PENSEE"];

                            break;
                        case 2 :
                            if($n2==$notif_notifier["NUM_CONTRIBUTION"]) goto jump;
                            $this->getNotificationRef($notif_notifier, "NUM_CONTRIBUTION","contribution", $_POST["num_utilisateur"],
                                $notif_notifier["CODE_NOTIF"],"R","a commenté ta contribtution",
                                "ont commenté ta contribtution", "CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");
                            $n2=$notif_notifier["NUM_CONTRIBUTION"];
                            break;
                        case 22 :
                            if($n22==$notif_notifier["NUM_CONTRIBUTION"]) goto jump;
                            $this->getNotificationRef($notif_notifier, "NUM_CONTRIBUTION","contribution", $_POST["num_utilisateur"],
                                $notif_notifier["CODE_NOTIF"],"V","t'a donné raison sur ta contribtution",
                                "t'ont donné raison sur ta contribtution","CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");
                            $n22=$notif_notifier["NUM_CONTRIBUTION"];
                            break;
                        case 221 :
                            if($n221==$notif_notifier["NUM_PENSEE"]) goto jump;

                            $this->getNotificationRef($notif_notifier, "NUM_PENSEE","pensee", $_POST["num_utilisateur"],
                                $notif_notifier["CODE_NOTIF"],"R","a commenté un contenu de ta pensée",
                                "ont commenté un contenu de ta pensée", "CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");

                            $n221=$notif_notifier["NUM_PENSEE"];
                            break;
                        case 3 :
                            if($n3==$notif_notifier["NUM_COMMENTAIRE"]) goto jump;
                            $this->getNotificationRef($notif_notifier, "NUM_COMMENTAIRE","commentaire", $_POST["num_utilisateur"],
                                $notif_notifier["CODE_NOTIF"],"R","a répondu à ton commentaire",
                                "ont répondu à ton commentaire", "CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");
                            $n3=$notif_notifier["NUM_COMMENTAIRE"];
                            break;
                        case 33 :
                            if($n33==$notif_notifier["NUM_COMMENTAIRE"]) goto jump;
                            $this->getNotificationRef($notif_notifier, "NUM_COMMENTAIRE","commentaire", $_POST["num_utilisateur"],
                                $notif_notifier["CODE_NOTIF"],"V","t'a donné raison sur ton commentaire",
                                "t'ont donné raison sur ton commentaire", "CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");
                            $n33=$notif_notifier["NUM_COMMENTAIRE"];
                            break;
                        case 321 :
                            if($n321==$notif_notifier["NUM_PENSEE"]) goto jump;

                            $this->getNotificationRef($notif_notifier, "NUM_PENSEE","pensee", $_POST["num_utilisateur"],
                                $notif_notifier["CODE_NOTIF"],"R","a commenté un contenu de ta pensée",
                                "ont commenté un contenu de ta pensée", "CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");
                            $n321=$notif_notifier["NUM_PENSEE"];
                            break;
                        case 5 :
                            if($n5==$notif_notifier["NUM_PENSEE"]) goto jump;
                            $this->getNotificationRef($notif_notifier, "NUM_PENSEE","pensee", $_POST["num_utilisateur"],
                                $notif_notifier["CODE_NOTIF"],"M","a modifié la valeur de son vote sur ta pensée",
                                "ont modifié la valeur de leur vote sur ta pensée", "CODE_NOTIF", "NUM_UTILISATEUR", "DATE_NOTIFIER");
                            $n5=$notif_notifier["NUM_PENSEE"];
                            break;
                        default:
                            echo "Bad Ref";

                    }

                   // echo $notif_notifier["CODE_NOTIF"]." $notif_notif_count";


                    jump:;
                }

            }
            if(!empty($notifs_aussi)){

                foreach ($notifs_aussi as $n => $notif_aussi) {

                    switch($notif_aussi["CODE_AUSSI"]){
                        case 4:
                            if($n4==$notif_aussi["NUM_PENSEE"]) goto jumpaussi;


                            $this->getNotificationRef($notif_aussi,"NUM_PENSEE","pensee", $_POST["num_utilisateur"],
                                $notif_aussi["CODE_AUSSI"],"V","a aussi comme toi voté la pensée",
                                "ont aussi comme toi voté la pensée",
                                "CODE_AUSSI", "NUM_UTILISATEUR", "DATE_AUSSI");
                            $n4=$notif_aussi["NUM_PENSEE"];

                            break;
                        case 44 :
                            if($n44==$notif_aussi["NUM_PENSEE"]) goto jumpaussi;

                            $this->getNotificationRef($notif_aussi, "NUM_PENSEE","pensee", $_POST["num_utilisateur"],
                                $notif_aussi["CODE_AUSSI"],"C","a aussi comme toi apporté une contribution à la pensée",
                                "ont aussi comme toi apporté une contribution à la pensée", "CODE_AUSSI", "NUM_UTILISATEUR", "DATE_AUSSI");
                            $n44=$notif_aussi["NUM_PENSEE"];

                            break;

                        case 444 :
                            if($n444==$notif_aussi["NUM_PENSEE"]) goto jumpaussi;
                            $this->getNotificationRef($notif_aussi, "NUM_CONTRIBUTION","contribution", $_POST["num_utilisateur"],
                                $notif_aussi["CODE_AUSSI"],"R","a aussi comme toi commenté la contribution",
                                "ont aussi comme toi commenté la contribution", "CODE_AUSSI", "NUM_UTILISATEUR", "DATE_AUSSI");

                            $n444=$notif_aussi["NUM_PENSEE"];
                            break;
                        default:
                            echo "Bad Ref";

                    }

                    // echo $notif_notifier["CODE_NOTIF"]." $notif_notif_count";


                    jumpaussi:;
                }


            }
            krsort($this->notifs_li);
            $data = implode("",$this->notifs_li);
            if(sizeof($this->notifs_li)==0) {
                $data = "<div class=\"no-notif1\">Aucune Notification :(</div>
                                <div class=\"no-notif2\">Faut Poster, Contribuer ou Voter :)</div>";
            }

            header('Content-type: application/json');
            $success['notifications']=$data;
            $success['notification_count']=$this->nombre_notif;

            $success['success']=1;
            die(json_encode($success));
        }

        //Gestion End

        if(isset($_POST["maj_vu"]) && isset($_POST["num_pensee"]) ){
            //mettre toutes les pensée de num_utilisateur et < date de num_pensee_first à vu

            $date_notif = $utilisateurModel->getDateNotif($_POST["num_utilisateur"], $_POST["num_pensee"], $_POST["num_contribution"],
                $_POST["num_commentaire"], $_POST["num_code"]);

            $utilisateurModel->majVuNotif($_POST["num_utilisateur"], $date_notif);
            $success['success']=1;
            die(json_encode($success));
        }

        if(isset($_POST["maj_lu"]) && isset($_POST["num_pensee"])){
            $utilisateurModel->majLuNotif($_POST["num_utilisateur"], $_POST["num_pensee"], $_POST["num_contribution"],
                                                    $_POST["num_commentaire"], $_POST["num_code"]);
            $success['success']=1;
            die(json_encode($success));
        }


        $nbrePensees = $utilisateurModel->getNombrePensee();
        $penseesParPage=20;
        $nbrePagePensees = ceil($nbrePensees["NBRE_PENSEE"]/$penseesParPage);
        $currentPage = 1;
        $page->nbrePagePensees = $nbrePagePensees;

        if(@$_GET['p'] && @$_GET['p']>0 && @$_GET['p']<=$nbrePagePensees){
            $currentPage=$_GET['p'];
        }else{
            $currentPage=1;
        }
        $page->currentPage = $currentPage;
        $apensee=$utilisateurModel->getPenseeUtilisateur($currentPage, $penseesParPage);
        $page->allpensee = $apensee;

        $page->allcontribution = array();

        $allcontribution=array();
        foreach($page->allpensee as $apensee){
            $allcontribution[$apensee["NUM_PENSEE"]]=$utilisateurModel->getContributionFromPensee($apensee["NUM_PENSEE"]);
            //$comments_by_contribution = $utilisateurModel->getCommentairesFromContribution($allcontribution["NUM_CONTRIBUTION"]);
        }
        $page->allcontribution=$allcontribution;
/*
        $comments_by_contribution=[];

        foreach($page->allcontribution as $contribution){
            $comments_by_contribution = $utilisateurModel->getCommentairesFromContribution($contribution["NUM_CONTRIBUTION"]);
        }
        $page->comments_by_contribution=$comments_by_contribution;
        ;

            */


        if (!\F3il\Authentication::isAuthenticated()) {

            if (isset($_SESSION['success'])) unset($_SESSION['success']);
            if (isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    setcookie($name, '', time()-1000);
                    setcookie($name, '', time()-1000, '/');
                }
            }



            \F3il\HttpHelper::redirect('?controller=index&action=index');



        }

        if (!\F3il\Request::isPost()) {
            return;
        }

        $form_poster->loadData($_POST);
        if (!$form_poster->validate()) {
            return;
        }

        if(\F3il\Request::isPost()) {
            $res=$utilisateurModel->posterPensee($form_poster->getData());
            header('Content-type: application/json');
            $success['success']=$res;
            die(json_encode($success));
        }




        if (!\F3il\Authentication::isAuthenticated()) {
            \F3il\HttpHelper::redirect('?controller=index&action=index');
        }
    }


    public function contribuerAction(){
        try{
            $form_contribuer = new ContribuerForm('?controller=utilisateur&action=posterpensee', \F3il\Form::CREATE_MODE);
            $form_contribuer->loadData($_POST);

            if($_SERVER['REQUEST_METHOD']!='POST')
            {
                http_response_code(403);
                die();
            }

            $utilisateurModel= new UtilisateurModel();

            $data = [
                'NUM_UTILISATEUR'=>$_POST['num_utilisateur'],
                'NUM_PENSEE'=>$_POST['num_pensee'],
                'LIBELLE_CONTRIBUTION'=>$_POST['libelle_contribution'],
                'TYPE_CONTRIBUTION'=>$_POST['type_contribution'],
                'NUM_PAGE'=>$_POST['num_page'],
            ];
            $user = \F3il\Authentication::getUserData();
            $userID=$user['NUM_UTILISATEUR'];

            $res = $utilisateurModel->posterContribution($data,$userID);
            $datas=$utilisateurModel->getContributionFromPensee($data["NUM_PENSEE"]);
            $contributions = json_decode(json_encode($datas), FALSE);

            $liveContribution=[];
            foreach($contributions as $k=>$contribution){

                if($contribution->NUM_UTILISATEUR==$data['NUM_UTILISATEUR']
                    && $contribution->NUM_PENSEE==$data['NUM_PENSEE']
                        && $contribution->LIBELLE_CONTRIBUTION==$data['LIBELLE_CONTRIBUTION']
                            && $contribution->TYPE_CONTRIBUTION==$data['TYPE_CONTRIBUTION'])
                {

                                        $LIBELLE_ANNEE_ETUDE=$contribution->LIBELLE_ANNEE_ETUDE;
                                        $DATE_CONTRIBUTION = date("d-m-Y  H:i", strtotime($contribution->DATE_CONTRIBUTION));
                                        $LIBELLE_CONTRIBUTION=$contribution->LIBELLE_CONTRIBUTION;
                                        $NUM_CONTRIBUTION =$contribution->NUM_CONTRIBUTION;
                                        $NUM_UTILISATEUR=$contribution->NUM_UTILISATEUR;
                                        $NOMBRE_LIKE_CONTRIBUTION=$contribution->NOMBRE_LIKE_CONTRIBUTION;

                }

            }
            $couleurContribution="";
            $libelleContribution = strip_tags($LIBELLE_CONTRIBUTION);
            if (preg_match("/^Oui je le pense aussi, autant plus qu(.)/", $libelleContribution)) {
                $couleurContribution = "deepskyblue !important";
            } else if(preg_match("/^Non je ne le pense pas. En effet(.)/", $libelleContribution)){
                $couleurContribution = "indianred !important";
            }else if(preg_match("/^Je ne dirai ni oui ni non. En effet(.)/", $libelleContribution)){
                $couleurContribution = "lightslategrey !important";
            }
            $data="

                <tr class='my$NUM_CONTRIBUTION'>
                    <td style=\"border-left-color:$couleurContribution\" class=\"mycontibution\" id=\"mycontibution$NUM_CONTRIBUTION\">

                        <small>Contribution d'un $LIBELLE_ANNEE_ETUDE</small>
                        <small style=\"float: right\">$DATE_CONTRIBUTION</small>
                         <P>
                         <div class=\"box-contribution\">
                            <div class=\"check-btn-contribution\" data-ref=\"contribution\" data-num_ref=\"$NUM_CONTRIBUTION\" data-num_utilisateur=\"$NUM_UTILISATEUR\">
                                <div class=\"check-vote\"><i class=\"fa fa-check-circle fa-2x\" aria-hidden=\"true\"></i></div>
                                <div id=\"check-count\">$NOMBRE_LIKE_CONTRIBUTION</div>
                            </div>
                            <div style=\"margin-bottom: -20px\" class='contribution-libelle show'>$LIBELLE_CONTRIBUTION</div>
                         </div>
                         </P>
                        <a href=\"#\" class=\"small-Ed2\" data-num_ref=\"$NUM_CONTRIBUTION\">Commentaires(0)</a>

                    </td>
                </tr>
                <tr class='my$NUM_CONTRIBUTION'>
                    <td class=\"td-comment comment-box$NUM_CONTRIBUTION\"  hidden>

                        <div method='POST' class=\"panel-body\" id='form-comment$NUM_CONTRIBUTION' action=''>



                            <div class='form-group'>
                                <textarea placeholder=\" Ecris ton commentaire ici :)\" id='libelle' rows='1' name='libelle' class='col-md-10' required autofocus ></textarea>
                                <input type='hidden' name='NUM_UTILISATEUR' value='$NUM_UTILISATEUR' id='user_id'>
                                <input type='hidden' name='NUM_CONTRIBUTION' value='$NUM_CONTRIBUTION' id='contribution_id'>
                                <input type='hidden' name='PARENT_NUM_COMMENTAIRE' value='0' id='parent_id'>
                                <?php \F3il\CsrfHelper::csrf(); ?>


                            </div>
                            <div class='form-group'>

                                <button class='btn btn-primary col-md-2 btn-comment' type='submit'>Commenter</button>
                            </div>
                        </div>
                    </td>
                </tr>";
            header('Content-type: application/json');
            $success['contribution']=$data;
            $success['success']=$res;
            $success['num_contribution']=$NUM_CONTRIBUTION;

            die(json_encode($success));

        }
        catch (\Exception $e){
            \F3il\Messenger::setMessage("Error".$e->getMessage());
        }
        //\F3il\HttpHelper::redirect('?controller=utilisateur&action=posterpensee');
    }

    public function commentaireAction(){

        $utilisateurModel= new UtilisateurModel();

        if(isset($_POST['LIBELLE_COMMENTAIRE']) && !empty($_POST['LIBELLE_COMMENTAIRE'])){

            $parent_id = isset($_POST["PARENT_NUM_COMMENTAIRE"])? $_POST["PARENT_NUM_COMMENTAIRE"]:0;

            $depth=0;
            $depth = $utilisateurModel->parentexist($parent_id, $depth);

            $form_commentaire = new CommentaireForm('?controller=utilisateur&action=posterpensee', \F3il\Form::CREATE_MODE);
            $form_commentaire->loadData($_POST);
            $user = \F3il\Authentication::getUserData();
            $userID=$user['NUM_UTILISATEUR'];

            $idLiveCommentaire=$utilisateurModel->posterCommentaire($form_commentaire->getData(), $depth, $userID, $_POST["num_page"]);


            $datas=$utilisateurModel->getCommentairesFromContribution($_POST["NUM_CONTRIBUTION"]);
            $commentaires = json_decode(json_encode($datas), FALSE);
           // echo '<pre>'.print_r($commentaires).'</pre>';

            foreach($commentaires as $k=>$commentaire){

                if($commentaire->NUM_UTILISATEUR==$_POST['NUM_UTILISATEUR']
                    && $commentaire->NUM_CONTRIBUTION==$_POST['NUM_CONTRIBUTION']
                    && $commentaire->NUM_COMMENTAIRE==$idLiveCommentaire)
                {
                    $NUM_UTILISATEUR=$commentaire->NUM_UTILISATEUR;
                    $userID=$commentaire->NUM_UTILISATEUR+421;
                    $DATE_COMMENTAIRE=date("d-m-Y  H:i", strtotime($commentaire->DATE_COMMENTAIRE));
                    $LIBELLE_COMMENTAIRE=$commentaire->LIBELLE_COMMENTAIRE;
                    $NUM_COMMENTAIRE=$commentaire->NUM_COMMENTAIRE;
                    $NUM_CONTRIBUTION=$commentaire->NUM_CONTRIBUTION;
                    $NOMBRE_LIKE_COMMENTAIRE=$commentaire->NOMBRE_LIKE_COMMENTAIRE;
                    $DEPTH=$commentaire->DEPTH;

            $reply="reply";

            if($DEPTH==3) $reply="noreply";


                    $PIXEL=0;
                    $PIXEL=($DEPTH==0)?$PIXEL=0:$PIXEL=50;
                    $PIXEL = $PIXEL.'px';
                }

            }

            $data="

<div class=\"panel panel-default\"  style=\"background-color: #f9f9f9; margin: 0;margin-left: $PIXEL;\" id=\"comment-$NUM_COMMENTAIRE\">
    <div class=\"panel-body\" style=\"margin-top: -10px;margin-bottom: -25px\">
        <div><small style=\"font-weight: bold;\">user$userID</small><small  style=\"float: right\">$DATE_COMMENTAIRE</small></div>
        <div style=\"display: flex;\">
            <div class=\"check-btn-commentaire\" data-ref=\"commentaire\" data-num_ref=\"$NUM_COMMENTAIRE\" data-num_utilisateur=$NUM_UTILISATEUR>
                <div class=\"check-vote\"><i class=\"fa fa-check-circle fa-lg\" aria-hidden=\"true\"></i></div>
                <div id=\"check-count\">$NOMBRE_LIKE_COMMENTAIRE</div>
            </div>
            <div style=\"margin-bottom: -33px; margin-top: -5px;width: 85%\" id=\"comment-$NUM_COMMENTAIRE\"  class=\"libelle-commentaire \"><p>$LIBELLE_COMMENTAIRE</p></div>
        </div>
        <p >

        <div  class=\"$reply\" data-id=\"$NUM_COMMENTAIRE\" data-contribution=\"$NUM_CONTRIBUTION\">
            <a  style=\"font-size: 12px !important; margin-left: 50px\">Répondre</a>
        </div>

        </p>

    </div>
</div>";
            header('Content-type: application/json');
            $success['num_commentaire']=$NUM_COMMENTAIRE;
            $success['commentaire']=$data;
            die(json_encode($success));

        }else{
            $messenger = new Messenger_edgarHelper('danger', 'Rien n\'a été posté');
            $messenger->messenger();
        }


        if(isset($_GET['id_comment']))
        {
            $utilisateurModel->deletecommentaire($_GET['id_comment']);
            $messenger = new Messenger_edgarHelper('success', 'Le commentaire a bien été supprimé');
            $messenger->messenger();
        }
        \F3il\HttpHelper::redirect('?controller=utilisateur&action=posterpensee');
    }


//Action exceptionnelle pour l'Ajax des boutons
    public function likeAction()
    {
        $utilisateurModel = new utilisateurModel();
        $user = \F3il\Authentication::getUserData();
        $userID=$user['NUM_UTILISATEUR'];



        //On veut appeler ce fichier en post
        if($_SERVER['REQUEST_METHOD']!='POST')
        {
            http_response_code(403);
            die();
        }

        //On peut voter pour ce type de contenu
        $accepted_refs = ['pensee', 'contribution', 'commentaire'];

        if(!in_array($_POST['ref'], $accepted_refs))
        {
            http_response_code(403);
            die();
        }

        //On déclenche le vote
        if(isset($_POST['vote']))
        {
            if ($_POST['vote'] == 1) {
                $success=$utilisateurModel->like($_POST['ref'], $_POST['num_ref'], $userID, $_POST['num_page']);
                // die('ok');

            } else {
                $success=$utilisateurModel->dislike($_POST['ref'], $_POST['num_ref'], $userID, $_POST['num_page']);

            }

            $likes=$utilisateurModel->getLikePensee($_POST['ref'],$_POST['num_ref'], $_POST['num_page']);
            header('Content-type: application/json');
            $likes['success']=$success;
            die(json_encode($likes));

        }

        if(isset($_POST['check_contribution']))
        {
            $success = $utilisateurModel->checkcontribution($_POST['ref'], $_POST['num_ref'], $userID, $_POST['num_page']);
            $likes=$utilisateurModel->getcheckcontribution($_POST['ref'],$_POST['num_ref'], $_POST['num_page']);
            header('Content-type: application/json');
            $likes['success']=$success;
            die(json_encode($likes));
        }

        if(isset($_POST['check_comment']))
        {
            $success = $utilisateurModel->checkcommentaire($_POST['ref'], $_POST['num_ref'], $userID, $_POST['num_page']);
            $likes=$utilisateurModel->getchecommentaire($_POST['ref'],$_POST['num_ref'], $_POST['num_page']);
            header('Content-type: application/json');
            $likes['success']=$success;
            die(json_encode($likes));
        }
    }



    public function ajoututilisateurAction()
    {
        $page = \F3il\Page::getInstance();
        $page->setTemplate('index');
        $page->setView('index');
        
        $form = new NouveauutilisateurForm('?controller=utilisateur&action=ajoututilisateur', \F3il\Form::CREATE_MODE);
        if (!\F3il\Request::isPost()) {
            return;
        }
        if (!\F3il\CsrfHelper::checkToken()) {
            throw new \F3il\Error("Erreur formulaire " . print_r($_SESSION, true) . ' ' . print_r($_POST, true));
        }
        $utilisateurModel = new utilisateurModel();
        $form->loadData($_POST);
        if (!$form->validate()) {
            return;
        }
        if ($utilisateurModel->loginUtilisateur($_POST['email']) == 1) {
            \F3il\Messenger::setMessage("email déjà utilisé");
            return;
        }
        $utilisateurModel->creerutilisateur($form->getData());
        \F3il\Messenger::setMessage("Inscription réussi");
    }

    public function deconnecterAction()
    {

        if(isset($_COOKIE['email'])){
            setcookie('email', null, time() - 31536000*3);

        }
        \F3il\Authentication::logout();
        \F3il\HttpHelper::redirect('?controller=index&action=index');
        exit();
    }


}





