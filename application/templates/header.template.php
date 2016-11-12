<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');

?>
<!DOCTYPE html>
<html>
<head> 
    <title>Thinknfree</title> 
    <meta charset="UTF-8">  
    <link href="./library/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/> 
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="./css/posterPensee.css" rel="stylesheet" type="text/css"/> 
    <link href="./css/mgSuggestion.css" rel="stylesheet" type="text/css"/> 
    <link rel="stylesheet" type="text/css" media="screen" href="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.css" />
    <style type="text/css">
        a.fancybox img {
            border: none;
            box-shadow: 0 1px 7px rgba(0,0,0,0.6);
            -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
        }
    </style>
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
</head> 


<body class="jumbotron"> 
<header>

    <nav class="navbar navbar-inverse navbar-fixed-top navbar-default ">
        <div class="container">
            <div class="navbar-header">

                <ul class="nav navbar-nav">
                    <li><a class="navbar-brand" href="?controller=apropos&action=apropos"><img src="./images/libre_logo.png" height="25" width="38" style="margin-top: 3px; margin-right: -20px;"></a></li>
                    <li>
                        <a href="?controller=apropos&action=apropos" class="header-text">A propos</a>
                    </li>

                </ul>
            </div>
            <ul class="nav navbar-nav .nav-center" id="top-nav" >

                <li id="notification_li">
                    <span id="notification_count" hidden></span>
                    <a href='?controller=utilisateur&action=posterpensee' class="header-text" id="notificationLink"><img src="./images/libre_logo.png" height="25" width="38" style="margin-top: 3px; margin-right: -20px;"></a>
                    <div id="notificationContainer">
                        <div id="notificationTitle">Notifications</div>
                        <div id="notificationsBody" class="notifications scroll">
                            <ul class="nav" id="notifications-ul">

                            </ul>
                        </div>
                        <div id="notificationFooter"><a href="#" class="notif-link-footer"><i class="material-icons">sentiment_satisfied</i></a></div>
                    </div>
                </li></ul>

            <ul class="nav navbar-nav navbar-right">

                <li>
                    <a href='?controller=utilisateur&action=posterpensee' class="header-text">Accueil</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="margin-top: 5px; color:dimgrey;" onmouseover="this.style.color='#1e90fe';this.style.color='#1e90fe';" onmouseout="this.style.background='';this.style.color='dimgrey';">
                        <?php $user = \F3il\Authentication::getUserData();
                        $userID=$user['NUM_UTILISATEUR'];
                        $userEmail = $user['EMAIL'];
                        echo $userEmail;?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href='?controller=profil&action=suggestion'>Profil</a></li>
                        <li><a href="?controller=utilisateur&action=deconnecter">Se Déconnecter</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<?php $this->insertView();?>


