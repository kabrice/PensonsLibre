<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');

$funct = new \Pensonslibre\FunctionsHelper();

?>

<div class="row"> 
    <div class="col-lg-6 col-lg-offset-3">	  
        </div> 
</div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="alert-placeholder">

        </div>
        <div class="panel panel-info" id="edgar-code">
            <?php
            if(isset($_SESSION['erreur_password']) && $_SESSION['erreur_password']==1 )
            {
                unset($_SESSION['erreur_password']);
                $msg=new \Pensonslibre\Messenger_edgarHelper('Désolé il y a une erreur, mot de passe inconnu :(', 'danger');
                echo $msg->messenger();

            }

            if(isset($_SESSION['confirm_dif']) && $_SESSION['confirm_dif']==1 )
            {
                unset($_SESSION['confirm_dif']);
                $msg=new \Pensonslibre\Messenger_edgarHelper('Désolé la confirmation ne correspond pas :(', 'danger');
                echo $msg->messenger();

            }
            ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-center"><h2><b>Recrées toi un mot de passe :)</b></h2></div>
<!-- reset.form -->
                        <?php
                        $this->form_reset->render();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./library/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="./library/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/javascript"></script>
</body>