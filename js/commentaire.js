$(document).ready(function(){
    var countClick =0;
    var countClickReply=0;
    var num_page = $("#currentPage").text();
    $('.reply').click(function(e){

        e.preventDefault();
        var $this = $(this);
        var contribution_id = $this.data('contribution');
        var $form = $('#form-reply'+contribution_id);
        $form.removeAttr("hidden");
        var parent_id = $this.data('id');
        var $comment = $('#comment-'+parent_id);
        $form.find('#parent_id').val(parent_id);
        $form.appendTo($comment);

    })

    $('.noreply').click(function(e){

        e.preventDefault();
        var $this = $(this);
        var $a = $this.find('a');

        if(countClickReply % 2 === 0)
            $a.text('Oups..!  J\'ai été arrêté pour excès de réponse :(');
        else
            $a.text('Répondre');
        countClickReply++;

    })

    $('.btn-comment').click(function(e){

        e.preventDefault();




        var $parentComment=$(this).parent().parent();
        var $parentTest=$(this).parent().parent().parent().parent().parent().parent();

        if($parentTest.data('depth')>=0) $parentComment=$(this).parent().parent().parent().parent().parent().parent();

        var num_utilisateur = $parentComment.find('#user_id').val();
        var num_contribution = $parentComment.find('#contribution_id').val();
        var parent_num_commentaire = $parentComment.find('#parent_id').val();
        var libelle_commentaire = $parentComment.find('#libelle').val();

        var $nbreCommentaire = $('main').find('#nbre-commentaire'+num_contribution);

        $.post('?controller=utilisateur&action=commentaire', {
            NUM_UTILISATEUR: num_utilisateur,
            NUM_CONTRIBUTION: num_contribution,
            LIBELLE_COMMENTAIRE: libelle_commentaire,
            PARENT_NUM_COMMENTAIRE: parent_num_commentaire,
            num_page: num_page
        }).done(function (data, textstatus, jqXHR) {
            console.log(data);
            $parentComment.find('#libelle').val('');
            var nbreCommentaire = parseInt($nbreCommentaire.text());
            $nbreCommentaire.text(nbreCommentaire+1);
            var mychild=$(data.commentaire);
            $parentComment.after(mychild);
            $('.livecomment'+data.num_commentaire).hide().fadeIn(800);
            mychild.find('.check-btn-commentaire').click(function(e){
                e.preventDefault();
                var $this=$(this);
                $(this).removeClass('is-checked');
                $.post('?controller=utilisateur&action=like', {
                    ref:$(this).data('ref'),
                    num_ref:$(this).data('num_ref'),
                    num_utilisateur:$(this).data('num_utilisateur'),
                    check_comment : 1,
                    num_page: num_page
                }).done(function(data, textstatus, jqXHR){
                    console.log(data);

                    var nblike=$this.find("#check-count");
                    nblike.html(data.NOMBRE_LIKE_COMMENTAIRE);
                    $this.removeClass('is-checked');

                    if(data.success) {
                        $this.addClass('is-checked');
                    }

                }).fail(function(jqXHR, textstatus, errorThrown){
                    console.log(jqXHR);
                });
            });
        }).fail(function (jqXHR, textstatus, errorThrown) {
            console.log(jqXHR);
        });
    })

    $('.check-btn-commentaire').click(function(e){
        e.preventDefault();
        var $this=$(this);
        $(this).removeClass('is-checked');
        $.post('?controller=utilisateur&action=like', {
            ref:$(this).data('ref'),
            num_ref:$(this).data('num_ref'),
            num_utilisateur:$(this).data('num_utilisateur'),
            check_comment : 1,
            num_page: num_page
        }).done(function(data, textstatus, jqXHR){
            console.log(data);

            var nblike=$this.find("#check-count");
            nblike.html(data.NOMBRE_LIKE_COMMENTAIRE);
            $this.removeClass('is-checked');
            if(data.success) {
                $this.addClass('is-checked');
            }

        }).fail(function(jqXHR, textstatus, errorThrown){
            console.log(jqXHR);
        });
    });

    $('.small-Ed2').click(function(e){
        e.preventDefault();
        //alert();
        var numContribution = $(this).data('num_ref');

        var $commentBox = $('.comment-box'+numContribution);
        $commentBox.removeAttr("hidden");

        countClick++
        if(countClick % 2 === 0) $commentBox.attr("hidden", "hidden ");
    });


});