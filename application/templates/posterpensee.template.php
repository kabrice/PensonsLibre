<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');
$respo = $_SESSION['administrateur'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link href="css/bonbon.css" rel="stylesheet" type="text/css"/>
        <link href="library/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <link href="library/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/pensonslibre.css" rel="stylesheet" type="text/css"/>


        <link href="css/jquery-ui/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <link href="css/jquery-ui/jquery-ui.structure.css" rel="stylesheet" type="text/css"/>
        <link href="css/jquery-ui/jquery-ui.theme.css" rel="stylesheet" type="text/css"/>
        

        <link href="library/bootstrap-3.3.5-dist/css/bootstrap-theme.css" rel="stylesheet" type="text/css"/>
        <link href="library/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="library/bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        
        <link href="library/bootstrap-3.3.5-dist/css/bootstrap-theme.css" rel="stylesheet" type="text/css"/>
        <link href="library/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="library/bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="library/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
       


        <title>Titre</title>
        <style type="text/css">
            .ui-helper-clearfix::after {
                display: inline;  /* or whatever it should be */
            }
        </style>
    </head>

    <body>
        <div id='entete'>
            <span class='gros'>Suivi TR</span> <?php
            $user = \F3il\Authentication::getUserData();
            $userName = substr($user['nom'], 0, 1) . '.' . $user['prenom'];
            echo $userName;
            ?> <input id='recherche' type='text' value='Sujet ou élève'/><span style='margin-left:40px;'> Rechercher</span> <a href="?controller=utilisateur&action=deconnecter"><span class='droite' style='margin-top:15px;'>Se Déconnecter</span></a>
        </div>
        <div id='conteneur'>
            <div id='menu'>
                <span class='titre'>Les sujets <span class='droite'><?php echo $this->listerparTypeSujet["nbretotal"] ?></span></span>
                <div style='border-top:dashed 1px;padding-top:5px;padding-bottom:5px;'>
                    <span class='soustitre'><a href="?controller=rdvpensonslibre&action=sujetcoursprof&administrateur=<?php echo $respo; ?>">Sujets en cours</a></span><span class='droite'><?php echo $this->listerparTypeSujet["nbreencours"] ?></span></div>
                <div style='border-top:dashed 1px;padding-top:5px;padding-bottom:5px;'>
                    <span class='soustitre'><a href="?controller=rdvpensonslibre&action=sujetatribuesutilisateur&administrateur=<?php echo $respo; ?>">Sujets non attribués</a></span><span class='droite'><?php echo $this->listerparTypeSujet["nbreattr"] ?></span></div>
                <div style='border-top:dashed 1px;padding-top:5px;padding-bottom:5px;'>
                    <span class='soustitre'><a href="?controller=rdvpensonslibre&action=sujetterminerutilisateur&administrateur=<?php echo $respo; ?>">Sujets terminés</a></span><span class='droite'><?php echo $this->listerparTypeSujet["nbretermine"] ?></span>
                </div></div>
            <div id='corps'>
                <div style="display: block; min-height:110px;">
                    <!-- ZONE DE CONTENU -->
                    <?php
                    $this->insertView();
                    ?>
                </div>
            </div>
            <script src='js/jquery-1.11.3.min.js' type='text/javascript'></script>       
            <script src="js/jquery-2.1.4.js" type="text/javascript"></script>  
            <script src='js/jquery.dataTables.min.js' type='text/javascript'></script>
            <script src="js/jquery-ui.js" type="text/javascript"></script>
        
            <script src="js/ui.js" type="text/javascript"></script>
            <script src="js/uislider.js" type="text/javascript"></script>
            
            <p></p>

    </body>
</html>