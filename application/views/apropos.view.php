<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');
?>
<!DOCTYPE html>
<html>
<head>
    <title>A propos de PensonsLibre</title>
    <meta charset="UTF-8">

    <link href="./library/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="./css/posterPensee.css" rel="stylesheet" type="text/css"/>
    <link href="./css/apropos.css" rel="stylesheet" type="text/css"/>
    <link href="./css/code.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<header>

    <nav class="navbar navbar-inverse navbar-fixed-top navbar-default ">
        <div class="container">
            <div class="navbar-header">

                <ul class="nav navbar-nav">
                    <li><a class="navbar-brand" href="?controller=apropos&action=apropos"><img src="./images/libre_logo.png" height="25" width="38" style="margin-top: 3px; margin-right: -20px;"></a></li>
                    <li>
                        <a href="?controller=apropos&action=apropos" class="header-text" style="font-family: \"Helvetica Neue" !im;">A propos</a>
                    </li>

                </ul>
            </div>

        </div>
    </nav>
</header>

<main>
    <div class="container">
        <div class="row">
            <div class="card card-container" style="background-color: rgba(248, 248, 255, 0.66) !important;">
                <img style="width: 78%" src="./images/founder-team.jpg"/>
                <div style="width: 80%">
                ThinknFree est reseau social créé par des anciens étudiants de 3iL pour 3iL dont le but est
                d'aider les étudiants et le personnel administratif à partager et à évaluer leurs pensées.
                <br>Nous contacter à <a href="mailto:equipe@thinknfree.com">equipe@thinknfree.com</a> :)
                <br/>
                    © 2016
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
<script src="./library/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="./library/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="./js/ajax-upload.js" type="text/javascript"></script>
<script src="./library/ckeditor/ckeditor.js" type="text/javascript"></script>
</body>
</html>