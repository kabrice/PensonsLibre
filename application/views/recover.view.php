<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');

$funct = new \Pensonslibre\FunctionsHelper();
$_SESSION['previous']=$funct->token_generator();
?>
<body>
<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3">
        <div class="alert-placeholder">

        </div>
        <div class="panel panel-info" id="edgar-recover">

            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center"><h2><b>Réinitialises ton mot de passe :)</b></h2></div>
<!-- recover.form--><?php
                        $this->form_recover->render();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./library/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="./library/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/sweetalert2/3.1.1/sweetalert2.min.js"></script>


<?php
if(isset($_GET['expire']) && $_GET['expire']==$_SESSION['expire']){
    unset($_SESSION['expire']);
    $funct->sweetActivation('cookie');
}
?>
</body>