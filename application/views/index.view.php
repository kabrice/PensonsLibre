<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');

if(isset($this->form_login)||property_exists($this, 'form_login')) {
    $this->form_login->render();
    ?>
    <a href='?controller=recover&action=recover' class="forgot-password">Mot de passe oublié?</a>
<?php }?>
</div>
<!-- ***   Form2  *** -->
<?php
if(isset($this->form_inscription)||property_exists($this, 'form_inscription')) {?>
<div class="card card-container" id="inscription-cont">
    <p id="inscription-pag">Nouveau ? Allez inscrit toi, c'est génial ! </p>
    <?php
    $this->form_inscription->render();
    }
    ?>

</div>
</div>
</div>
</div>
</main>
</div>
</main>
<script src="js/cookiefacebook.js" type="text/javascript" ></script>
<script>
    setCookie("myhash", window.location.hash, 10);
</script>
<script src="https://cdn.jsdelivr.net/sweetalert2/3.1.1/sweetalert2.min.js"></script>
<!--<script src="./library/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/javascript"></script>-->
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.5.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>


<?php


$funct = new \Pensonslibre\FunctionsHelper();

/*$email3il=preg_match("/@3il\.fr$/", @$_POST['email'], $tab);
echo $_POST['confirmation'].'<br/>';
echo $_POST['motdepasse'].'<br/>';
echo $_POST['email_exists'].'<br/>';
echo $email3il;
die();
*/
$funct->sweetActivation('envoim_true');
if(isset($_SESSION['email_exists']) && $_SESSION['email_exists']==0) {
    //echo  "<script src=\"http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.5.min.js\" type=\"text/javascript\"></script>";
    unset($_SESSION['email_exists']);
    $funct->sweetActivation('envoim_true');
}


if(isset($_SESSION['token']) && !isset($_SESSION['previous']) && isset($_SESSION['compteactiver'])){
    unset($_SESSION['token']);
    unset($_SESSION['previous']);
    $funct->sweetActivation('mdpactivation');
}
if(isset($_GET['expire']) && $_GET['expire']==$_SESSION['expire']){
    unset($_SESSION['expire']);
    $funct->sweetActivation('cookie');
}

if(isset($_GET['reset']) && $_GET['reset']==$_SESSION['reset']){
    unset($_SESSION['reset']);
    $funct->sweetActivation('passwordsuccess');
}


if(isset($_SESSION['activer'])) {
    unset($_SESSION['activer']);
    $funct->sweetActivation('compteactive');
}


if(isset($_SESSION['facebook'])) {
    unset($_SESSION['facebook']);
    $funct->sweetActivation('nonconnecte');
}


?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-82045677-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>

