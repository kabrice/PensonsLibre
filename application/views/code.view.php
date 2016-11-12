<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');

$funct = new \Pensonslibre\FunctionsHelper();

?>



<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3">
        <div class="alert-placeholder">

        </div>
        <div class="panel panel-info" id="edgar-code">
            <?php
            if(isset($_SESSION['code_fail']) && $_SESSION['code_fail']==1 )
            {
                unset($_SESSION['code_fail']);
                $msg=new \Pensonslibre\Messenger_edgarHelper('Désolé, le code de validation est mauvais :(', 'danger');
                echo $msg->messenger();

            }
            ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center"><h2><b>Entre le code :)</b></h2></div>

<!-- code.form-->
                        <?php
                        $this->form_code->render();
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