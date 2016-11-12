<?php

defined('__PENSONSLIBRE__') or die('Acces Interdit');
?>
<link href='https://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>

<main>
    <div >
        <div id="label-post" >
            <table id="table-post">
                <tr><td id="td1">Dit ce que tu penses :</td></tr>
                <tr> <td id="td2">Expliques ta pensée :</td></tr>
                <tr><td id="td3">Décris ta pensée avec une image :</td></tr>

            </table>

            <div class="alert alert-info" id="note" style="margin: 100px 0 0 1010px; width: 200px; position: absolute; ">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <b>IMPORTANT :</b> <br><i>-> Ne t'inquiète surtout pas, tout ce que tu dis et partages reste dans l'anonymat TOTAL (personne ne saura
                    que c'est toi). :)</i><br>
                <i>-> Seul les étudiants de 3iL peuvent avoir accès au contenu du site. :)</i><br>
                <i>-> Lien pour copier les emojis : <a target="_blank" href="https://getemoji.com/">getemoji.com</a>.</i><br>
            </div>

        </div>
    <div class="container" style="width: 700px">

            <div class="card card-container">
                <?php
                $this->form_poster->render();
                $utilisateurModel = new \Pensonslibre\UtilisateurModel();
                $user = \F3il\Authentication::getUserData();
                $userID=$user['NUM_UTILISATEUR'];

                ?>
            </div>
            <br/><br/>
            <!--card2-->
            <?php

            //Test pagination



            $nombre=0;
            $comment_by_id=[];
            $childre=[];
            $liked= "";
            foreach ($this->allpensee as $apensee) {

                $vote_pensee = $utilisateurModel->getValeurVoteRef($apensee['NUM_PENSEE'], 'pensee', $userID);
                if($vote_pensee==1){
                    $liked="is-liked";
                }elseif($vote_pensee==-1){
                    $liked="is-disliked";
                }
                $nombre++;
                ?>
                <div id="<?=$apensee['NUM_PENSEE']?>">
                    <div class="card card-container" id="card-Ed" >

                        <table class="table table-bordered form-group pensee<?=$apensee['NUM_PENSEE']?>" width="670" cellpadding="0" border="0" align="center" cellspacing="0">
                            <?php $updatecount = $utilisateurModel->updateCount('pensee',$apensee['NUM_PENSEE']);?>
                            <tr><td><small class="pensee-header">Pensée d'un étudiant de <?=$apensee["LIBELLE_ANNEE_ETUDE"]?></small><small style="float: right"><?=date("d-m-Y  H:i", strtotime($apensee["DATE_PENSEE"]))?></small></td></tr>
                            <tr><td class="titre-pensee" style="border: none"><?=$apensee["TITRE_PENSEE"]?></td></tr>
                            <tr >
                                <td class="pensee-libelle " style="border: none"><div class="expandDiv"><?=$apensee["LIBELLE_PENSEE"];?></div></td>
                            </tr>
                            <tr style="margin-top: -20px">
                                <td style="border: none; " style="margin-top: -20px">
                                    <div style="margin-top: 10px">
                                        <img class="fancybox" id="img-pensee" style="margin-top: -20px" src="<?=$apensee["PHOTO_PENSEE"]?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" id="hidden" value="<?=$apensee["NUM_PENSEE"];?>">
                                    <div class="vote <?php echo $liked; $liked="";?>"  id="vote" <?=$apensee["NUM_PENSEE"];?> data-ref="pensee" data-num_ref="<?=$apensee["NUM_PENSEE"]; ?>" data-num_utilisateur="<?=$userID?>">

                                        <div class="vote_bar">
                                            <div class="vote_progress" style="width:<?= ($apensee["NOMBRE_LIKE_PENSEE"]+$apensee["NOMBRE_DISLIKE_PENSEE"])== 0 ? 100 : round(100 * ($apensee["NOMBRE_LIKE_PENSEE"]/($apensee["NOMBRE_LIKE_PENSEE"]+$apensee["NOMBRE_DISLIKE_PENSEE"])));?>%"></div>
                                        </div>
                                        <div class="vote_btns">
                                            <div class="vote_btn vote_like "><span class="ouinon">Oui</span> <span id="nombre_like" class="ouinon"><?=$apensee["NOMBRE_LIKE_PENSEE"]; ?></span></div>
                                            <div class="vote_btn vote_dislike"><span class="ouinon">Non </span><span id="nombre_dislike" class="ouinon"><?=$apensee["NOMBRE_DISLIKE_PENSEE"]; ?></span></div>
                                            <span style="margin-left: 65px"><a href="#form_contribution" class="link-contribution" data-num_pensee="<?=$apensee["NUM_PENSEE"]?>">Contributions(<div class="nbre-contribution"><?=count(@$this->allcontribution[$apensee["NUM_PENSEE"]]); ?></div>)</a></span>

                                            <div id="fb-root"></div>


                                            <script>
                                                window.fbAsyncInit = function() {
                                                    FB.init({
                                                        appId  : '1711396672460430',
                                                        status : true, // check login status
                                                        cookie : true, // enable cookies to allow the server to access the session
                                                        xfbml  : true  // parse XFBML
                                                    });
                                                };

                                                (function() {
                                                    var e = document.createElement('script');
                                                    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                                                    e.async = true;
                                                    document.getElementById('fb-root').appendChild(e);
                                                }());
                                            </script>




                                            <img id="share_button<?=$apensee["NUM_PENSEE"];?>" style="cursor: pointer; height: 35px;float: right; margin-top: -30px; margin-right: 30px; padding: 2px" src = "images/facebook_share.png">

                                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    $('#share_button<?=$apensee["NUM_PENSEE"];?>').click(function(e){
                                                        e.preventDefault();
                                                        FB.ui(
                                                            {
                                                                method: 'feed',
                                                                name: "<?=$apensee["TITRE_PENSEE"]?>",
                                                                link: 'https://www.thingether.com/?controller=utilisateur&action=posterpensee&facebook=true#<?=$apensee["NUM_PENSEE"];?>',
                                                                picture: 'https://www.thingether.com/<?=$apensee["PHOTO_PENSEE"]?>',
                                                                caption: 'THINGETHER.COM',
                                                                description: "<?= strip_tags($apensee["LIBELLE_PENSEE"])?>",
                                                                message: "Hey, je viens de lire cette pensée sur un réseau social de 3iL, il faut absolument que tu y jettes un coup d'oeil. C'est trop OUF !"
                                                            });
                                                    });
                                                });
                                            </script>

                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr class="form_contribution contribution-box<?=$apensee["NUM_PENSEE"]?>" hidden id="form_contribution_<?=$nombre?>" numpensee="<?=$apensee["NUM_PENSEE"];?>">
                                <?php
                                $this->form_contribuer->numuser=\F3il\Authentication::getUserId();
                                $this->form_contribuer->numpensee=$apensee["NUM_PENSEE"];
                                $this->form_contribuer->typec="y";

                                ?>
                                <?php
                                $hidden = "";
                                if($utilisateurModel->contributionexist($userID,$apensee["NUM_PENSEE"])==true)
                                {
                                    //echo $utilisateurModel->contributionexist($userID,$apensee["NUM_PENSEE"] );
                                    $hidden = "hidden";
                                }
                                ?>
                                <td class="td-pensee<?=$apensee["NUM_PENSEE"]?>" <?=$hidden?>> 
                                    <div id="contributions" data-nombre="<?=$nombre;?>">

                                        <div id="contribution-erreur<?=$nombre;?>" class="alert alert-danger" hidden="hidden">
                                            <a href="#" class="closeMe close" aria-label="close">&times;</a>
                                            'La contribution n\'a pas pu être soumise :(<br/> 
                                            Verifies qu' elle commence par au moins l\'une de ces expressions :<br/>&nbsp; 
                                            -> <strong> "Oui je le pense aussi, autant plus que"</strong><br/>&nbsp; 
                                            -> <strong> "Non je ne le pense pas. En effet"</strong><br/>&nbsp; 
                                            -> <strong> "Je ne dirai ni oui ni non. En effet"</strong><br/> 
                                            <em>Tu peux voir des exemples sur des contributions déjà postées.</em> ;-)'
                                        </div>
                                        <div id="contribution-erreur<?=$nombre;?>" class="alert alert-danger" hidden="hidden">
                                            <a href="#" class="closeMe close" aria-label="close">&times;</a>
                                            'Cette contribution existe déjà:(<br/> '
                                        </div>
                                        <textarea class="ta-contribution" id='tacontribution<?=$nombre;?>' required autofocus></textarea> 
                                        <input class="btn-contribution btn btn-primary col-md-2" style="height:300%; padding: 0px; bottom: 0px ;" type="submit" value="Contribuer" /> 
                                                                     <?php $this->form_contribuer->render();?>
                                    </div> 
                                </td>
                            </tr>

                            <tr class='livecontribution' hidden="hidden">
                                <td>

                                    <small>Contribution d'un </small>
                                    <small id='small-Ed2'></small>
                                    <P style='font-size: 25px; font-family:'Great Vibes';'><p></p> </P>
                                    <small id='small-Ed2'><a href='#'>Commentaires</a>(0)</small>

                                </td>
                            </tr>



                            <tr class='livecontribution' hidden="hidden">
                                <td>

                                    <div method='POST' id='form-comment28' action=''>



                                        <div class='form-group'>
                                            <textarea placeholder='Entre ton commentaire :)' id='libelle'  name='libelle' class='col-md-10' style='height:200%; padding: 0px;'  required autofocus ></textarea>
                                            <input type='hidden' name='NUM_UTILISATEUR' value='22'>
                                            <input type='hidden' name='NUM_CONTRIBUTION' value='28' id='contribution_id'>
                                            <input type='hidden' name='PARENT_NUM_COMMENTAIRE' value='0' id='parent_id'>
                                            <?php \F3il\CsrfHelper::csrf(); ?>


                                        </div>
                                        <div class='form-group'>

                                            <button class='btn btn-primary col-md-2 btn-comment' type='submit'>Commenter</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <?php
                            if(!is_null($this->allcontribution[$apensee["NUM_PENSEE"]])&&count($this->allcontribution[$apensee["NUM_PENSEE"]]))
                                foreach ($this->allcontribution[$apensee["NUM_PENSEE"]] as $acontribution) {
                                    $couleurContribution="";
                                    $votecontribution=($utilisateurModel->getValeurVoteRef($acontribution["NUM_CONTRIBUTION"], "contribution", $userID));
                                    $checkedContribtion = ($votecontribution==1)?"is-checked":"";

                                    $libelleContribution = strip_tags($acontribution["LIBELLE_CONTRIBUTION"]);
                                    if (preg_match("/^Oui je le pense aussi, autant plus qu(.)/", $libelleContribution)) {
                                        $couleurContribution = "deepskyblue !important";
                                    } else if(preg_match("/^Non je ne le pense pas. En effet(.)/", $libelleContribution)){
                                        $couleurContribution = "indianred !important";
                                    }else if(preg_match("/^Je ne dirai ni oui ni non. En effet(.)/", $libelleContribution)){
                                        $couleurContribution = "lightslategrey !important";
                                    }


                                    ?>
                                    <div >
                                        <!-- Contributions cachées -->
                                        <tr class="contribution-box<?=$apensee["NUM_PENSEE"]?>" hidden="hidden">
                                            <td style="border-left-color:<?=$couleurContribution?>" class="mycontibution" id="mycontibution<?=$acontribution["NUM_CONTRIBUTION"]?>">

                                                <small>Contribution d'un <?php echo $acontribution["LIBELLE_ANNEE_ETUDE"]; ?></small>
                                                <small style="float: right"><?=date("d-m-Y  H:i", strtotime($acontribution["DATE_CONTRIBUTION"]))?></small>
                                                <P>
                                                <div class="box-contribution">
                                                    <div  class="check-btn-contribution <?php echo $checkedContribtion; $checkedContribtion="";?>" data-ref="contribution" data-num_ref="<?=$acontribution["NUM_CONTRIBUTION"]; ?>" data-num_utilisateur="<?=$userID?>">
                                                        <div class="check-vote"><i class="fa fa-check-circle fa-2x" aria-hidden="true"></i></div>
                                                        <div id="check-count"><?=$acontribution["NOMBRE_LIKE_CONTRIBUTION"]?>
                                                        </div>
                                                    </div>
                                                    <div class="contribution-libelle" style="margin-bottom: -20px"><?php

                                                        echo $acontribution["LIBELLE_CONTRIBUTION"];?></div>
                                                </div>
                                                </P>
                                                <a href="#" class="small-Ed2" data-num_ref="<?=$acontribution["NUM_CONTRIBUTION"]; ?>">Commentaires(<div id="nbre-commentaire<?=$acontribution["NUM_CONTRIBUTION"]?>" style="display: inline-block"><?php $NBRE_COMMENTAIRE = $utilisateurModel->getnombrecommentaire($acontribution["NUM_CONTRIBUTION"]);
                                                        echo $NBRE_COMMENTAIRE['NBRE_COMMENTAIRE'];?></div>)</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-comment comment-hide comment-box<?=$acontribution["NUM_CONTRIBUTION"]; ?> "  hidden>
                                                <?php
                                                $data= $utilisateurModel->getCommentairesFromContribution($acontribution["NUM_CONTRIBUTION"]);
                                                $comments_by_contribution = json_decode(json_encode($data), FALSE);

                                                foreach($comments_by_contribution as $comment){
                                                    $comment_by_id[$comment->NUM_COMMENTAIRE]=$comment;
                                                    //echo $comment["LIBELLE_COMMENTAIRE"]."<br/>";
                                                }



                                                foreach($comments_by_contribution as $k=>$comment){

                                                    if($comment->PARENT_NUM_COMMENTAIRE!=0)
                                                    {
                                                        $comment_by_id[$comment->PARENT_NUM_COMMENTAIRE]->children[]=$comment;
                                                        unset($comments_by_contribution[$k]);
                                                    }

                                                }


                                                ?>
                                                <?php
                                                $this->form_commentaire->numuser=\F3il\Authentication::getUserId();
                                                $this->form_commentaire->numcontribution=$acontribution["NUM_CONTRIBUTION"];


                                                ?>

                                                <div method="POST" class="panel-body " id="form-comment<?=$acontribution["NUM_CONTRIBUTION"]?>" action="" >



                                                    <div class="form-group" >
                                                        <?php $this->form_commentaire->render();?>
                                                    </div>
                                                    <div class="form-group">

                                                        <button class="btn btn-primary col-md-2 btn-comment" type="submit" >Commenter</button>
                                                    </div>
                                                </div>



                                                <?php foreach($comments_by_contribution as $comment): ?>
                                                    <?php require('application/helpers/comment.php') ?>
                                                <?php endforeach; ?>
                                                <form class="" style="margin-bottom: -14px; margin-left: -2px" hidden="hidden" method="POST" id="form-reply<?=$acontribution["NUM_CONTRIBUTION"]?>" action="?controller=utilisateur&action=commentaire">



                                                    <div class="form-group ta-comment">
                                                        <?php $this->form_commentaire->render();?>
                                                    </div>
                                                    <div class="form-group" style="display: flex">

                                                        <button class="btn btn-primary col-md-2 btn-comment" type="submit" style="margin-top: -18px; width: 100px; margin-left: 5px;">Commenter</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    </div>
                                    <?php
                                }?>

                        </table>
                        <div id="fountainG"  class="loading<?=$apensee["NUM_PENSEE"];?>" hidden>
                            <div id="fountainG_1" class="fountainG"></div>
                            <div id="fountainG_2" class="fountainG"></div>
                            <div id="fountainG_3" class="fountainG"></div>
                            <div id="fountainG_4" class="fountainG"></div>
                            <div id="fountainG_5" class="fountainG"></div>
                            <div id="fountainG_6" class="fountainG"></div>
                            <div id="fountainG_7" class="fountainG"></div>
                            <div id="fountainG_8" class="fountainG"></div>
                        </div>
                    </div>
                </div>

            <?php }?>
            <ul class="pagination">
        <?php
            for($i=1; $i<=$this->nbrePagePensees; $i++){

                if($i==$this->currentPage){
                    echo "<li><a class=\"active\" href=\"#\">$i</a></li>";
                    $currentPage = $i;
                }else{
                    echo "<li><a href=\"https://localhost/projet_pensonslibre/?controller=utilisateur&action=posterpensee&p=$i\">$i</a></li>";
                }

            }

            ?>
            </ul>
    </div>
<div id="currentPage" hidden><?=$currentPage?></div>
</main>

<script src="./library/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="./js/ajax-upload.js" type="text/javascript"></script>
<script src="./js/app.js" type="text/javascript"></script>
<script src="./js/posterPensee.js" type="text/javascript"></script>
<script src="./js/contribution.js" type="text/javascript"></script>
<script src="./js/countLimit.js" type="text/javascript"></script>
<script src="./js/commentaire.js" type="text/javascript"></script>
<script src="./js/showmore.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/notification.js"></script>
<script src="./library/ckeditor/ckeditor.js" type="text/javascript"></script>
<script>

    CKEDITOR.replace( 'textareaEd', {
        uiColor: '#C9C9C9'
    });
    $('small').each(function(){
        var tet=$(this).text();
        var tet=tet.replace('0 jours', '');
        var tet=tet.replace('00 heures', '');
        var tet=tet.replace('00 minutes', '');
        var tet=tet.replace('00 secondes', '');
        var tet=tet.replace('00 min', '');
        var tet=tet.replace('00 sec', '');
        var tet=tet.replace('0 minutes', '');
        var tet=tet.replace('0 jrs', '');
        var tet=tet.replace('0 sec', '');
        $(this).text(tet);
    });
</script>
<?php
for($i=1; $i<$nombre+1;$i++)
{
    echo "<script>CKEDITOR.replace('tacontribution$i',{
           uiColor: '#F7F7F7'
});</script>";
}
?>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.min.js"></script>

<script type="text/javascript">
    $(function($){
        var addToAll = false;
        var gallery = false;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function(){
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('data-big') || $this.attr('src');
            var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
    });
    $.noConflict();

    if($(window.location.hash).length > 0){
        $('html, body').animate({ scrollTop: $(window.location.hash).offset().top}, 1000);
    }


</script>
<script>
    $(document).ready(function() {

        $('div.expandDiv').expander({
            slicePoint: 200, //It is the number of characters at which the contents will be sliced into two parts.
            widow: 2,
            expandSpeed: 0, // It is the time in second to show and hide the content.
            userCollapseText: 'Lire moins' // Specify your desired word default is Less.
        });

        $('div.expandDiv').expander();
    });


</script>
</body>


</html>


