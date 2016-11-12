<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thingether - Connexion ou inscription </title>
    <link href="library/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/bienvenue.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/3.1.1/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/3.1.1/sweetalert2.css">
</head>
<body >


<header>

    <nav class="navbar navbar-inverse">
        <div class="container">


            <ul class="nav navbar-nav">
                <li><a class="navbar-brand" href='?controller=apropos&action=apropos'><img src="images/libre_logo.png" height="25" width="38" style="margin-top:-2px; margin-right: -20px;"></a></li>
                <li>
                    <a href='?controller=apropos&action=apropos'>A propos</a>
                </li>

            </ul>
        </div>
    </nav>
</header>
<div class="jumbotron">
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div id="titreBienvenue">
                        <h1><strong>Bienvenue sur Thingether</strong></h1>
                        <h3 id="sstitreBienveue">« Pensons ensemble, pensons librement »</h3>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-container">


                        <!-- Form1 -->
                        <?php $this->insertView();?>

