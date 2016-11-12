<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');
?>
<main>
    <div class="container">

        <div class="card card-container">

        </div>
        <!--card2-->

        <?php foreach ($this->penseesinvalidees as $penseesinvalidees):?>
        <div class="card card-container" id="card-Ed">

                <table class="table table-bordered">
                    <tr><td><small>Pensee d'un étudiant de <?php echo $penseesinvalidees["DATE_PENSEE"]; ?></small><small id="small-Ed2"><?=$penseesinvalidees["DATE_PENSEE"]?></small></td></tr>
                    <tr><td><h3><?php echo $penseesinvalidees["TITRE_PENSEE"]; ?></h3></td></tr>
                    <tr>
                        <td><P><?php echo $penseesinvalidees["LIBELLE_PENSEE"]; ?></P></td>
                    </tr>
                    <tr>
                        <td>
                            <img src="<?php echo $penseesinvalidees["PHOTO_PENSEE"]; ?>" height="300" width="550">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <form method="POST" action="?controller=utilisateur&action=validerpensee">

                                <input type="hidden" name="num_pensee" value="<?php echo $penseesinvalidees["NUM_PENSEE"]?>"/>
                                <button class="btn btn-lg btn-primary btn-block" id="btn-Ed3" type="submit"> Valider</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>

                </table>
            </form>

        </div>
        <?endforeach;?>
    </div>
</main>
<script src="./library/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="./library/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="./js/ajax-upload.js" type="text/javascript"></script>
<script src="./library/ckeditor/ckeditor.js" type="text/javascript"></script>
</body>
</html>

<section class="slider">
    <div class="flexslider">
        <ul class="slides">
            <li>
                <img src="your_image_link.jpg" alt="">
            </li>
            <li>
                <img class="lazy" data-src="your_image_link.jpg" alt="">
            </li>
            <li>
                <img class="lazy" data-src="taro_mark_plage.jpg" alt="">
            </li>
            <li>
                <img class="lazy" data-src="your_image_link.jpg" alt="">
            </li>
            <li>
                <img class="lazy" data-src="your_image_link.jpg" alt="">
            </li>
        </ul>
    </div>
</section>