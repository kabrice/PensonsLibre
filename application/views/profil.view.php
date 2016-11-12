<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');
?>
    <head>
        <link href="./css/profil.css" rel="stylesheet" type="text/css"/> 
    </head>

<main>
    <div class="container">
        <div class="row">
            <div class="card card-container">
                <?php foreach ($this->infoutilisateur as $infoutilisateur):?>
                <table>
                    <tr>

                        <td>Pseudo : </td>
                        <td> <?php echo 'user'.($infoutilisateur['NUM_UTILISATEUR']+421) ?></td>
                    </tr>
                    <tr>
                        <td>Adresse email : </td>
                        <td> <?php echo $infoutilisateur['EMAIL'] ?></td>
                    </tr>
                    <tr>
                        <td>Année d'étude : </td>
                        <td> <?php echo $infoutilisateur['LIBELLE_ANNEE_ETUDE'] ?>
                            <a href="#">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a></td>
                    </tr>
                    <tr>
                        <td>Mode de passe : </td>
                        <td>
                            <a href="?controller=recover&action=recover">
                                Changer de mot de passe</span>
                            </a>
                        </td>
                    </tr>
                </table>
                <?endforeach;?>
            </div>

            <div class="card card-container" id="suggestion">
                <?php
                $this->form_suggestion->render();
                ?>
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
