
$(document).ready(function(){

    var num_page = $("#currentPage").text();
    $('.vote_like').click(function(e){
        e.preventDefault();
        var $vote = $(this).parent().parent();
        $vote.removeClass('is-liked is-disliked');
        vote(1,$vote);
    });
    $('.vote_dislike').click(function(e){
        var $vote = $(this).parent().parent();
        $vote.removeClass('is-liked is-disliked');
        e.preventDefault();
        vote(-1,$vote);
    })

    function vote(value,$vote)
    {
        $.post('?controller=utilisateur&action=like', {
            ref:$vote.data('ref'),
            num_ref:$vote.data('num_ref'),
            num_utilisateur:$vote.data('num_utilisateur'),
            vote : value,
            num_page: num_page
        }).done(function(data, textstatus, jqXHR){
            var nblike=$vote.find("#nombre_like");
            var nbdislike=$vote.find("#nombre_dislike");
            nblike.text(data.NOMBRE_LIKE_PENSEE);
            nbdislike.text(data.NOMBRE_DISLIKE_PENSEE);
            $vote.removeClass('is-liked is-disliked');
            if(data.success) {
                if (value == 1) {
                    $vote.addClass('is-liked');
                    //console.log(data);
                } else {
                    $vote.addClass('is-disliked');
                    //console.log(data);
                }
            }
            var percentage = Math.round(100*(data.NOMBRE_LIKE_PENSEE/(parseInt(data.NOMBRE_LIKE_PENSEE)+parseInt(data.NOMBRE_DISLIKE_PENSEE))));
            $('.vote_progress').css('width', percentage+'%');
        }).fail(function(jqXHR, textstatus, errorThrown){
            console.log(jqXHR);
        });
    }
})