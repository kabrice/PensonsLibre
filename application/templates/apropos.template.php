<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');
//$respo = $_SESSION['administrateur'];
?>
<!DOCTYPE html>
<html>
<head> 
    <title>PensonsLibre</title> 
    <meta charset="UTF-8">  
    <link href="./library/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/> 
    <link href="./css/posterPensee.css" rel="stylesheet" type="text/css"/> 
    <link href="./css/recover.css" rel="stylesheet" type="text/css"/> 
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/3.1.1/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/3.1.1/sweetalert2.css">
</head> 


<body class="jumbotron"> 
<header>

    <nav class="navbar navbar-inverse navbar-fixed-top navbar-default ">
        <div class="container">
            <div class="navbar-header">

                <ul class="nav navbar-nav">
                    <li><a class="navbar-brand" href="#"><img src="./images/libre_logo.png" height="25" width="38" style="margin-top: 3px; margin-right: -20px;"></a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<?php $this->insertView();?>


