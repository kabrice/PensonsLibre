<?php

defined('__PENSONSLIBRE__') or die('Acces Interdit');

$checkedCommentaire="";
$votecommentaire=($utilisateurModel->getValeurVoteRef($comment->NUM_COMMENTAIRE, "commentaire", $userID));
$checkedCommentaire= ($votecommentaire==1)?"is-checked":"";

?>
<div class="panel panel-default "  style="background-color: #f9f9f9; margin: 0" data-depth="<?=$comment->DEPTH;?>">
    <div class="panel-body" style="margin-top: -10px;margin-bottom: -25px">
        <div><small style="font-weight: bold;">user<?=$comment->NUM_UTILISATEUR+421?></small><small  style="float: right"><?=date("d-m-Y  H:i", strtotime($comment->DATE_COMMENTAIRE))?></small></div>
        <div style="display: flex;">
            <div class="check-btn-commentaire <?php echo $checkedCommentaire; $checkedCommentaire="";?>" data-ref="commentaire" data-num_ref="<?=$comment->NUM_COMMENTAIRE; ?>" data-num_utilisateur="<?=$userID?>">
                <div class="check-vote"><i class="fa fa-check-circle fa-lg" aria-hidden="true"></i></div>
                <div id="check-count"><?=$comment->NOMBRE_LIKE_COMMENTAIRE?></div>
            </div>
            <div style="margin-bottom: -33px; margin-top: -5px;width: 85%" id="comment-<?=$comment->NUM_COMMENTAIRE;?>"  class="libelle-commentaire "><p><?=htmlentities($comment->LIBELLE_COMMENTAIRE);?></p></div>
        </div>
        <p >
            <?php $reply="reply";
            if($comment->DEPTH==3) $reply="noreply";?>
        <div  class="<?=$reply?>" data-id="<?=$comment->NUM_COMMENTAIRE;?>" data-contribution="<?=$acontribution["NUM_CONTRIBUTION"]?>" >
            <a href="#" style="font-size: 12px !important; margin-left: 50px">Répondre</a>
        </div>

        </p>

    </div>
</div>




<?php \F3il\CsrfHelper::csrf(); ?>

<div style="margin-left: 50px">
    <?php if(isset($comment->children)):?>
        <?php foreach($comment->children as $comment):?>
            <?php require('application/helpers/comment.php'); ?>
        <?php endforeach;?>
    <?php endif;?>
</div>
