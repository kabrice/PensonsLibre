$(document).ready(function(){
    var countClick =1;
    var countContribution = 0;
    var num_page = $("#currentPage").text();
    var timeoutHandle = window.setTimeout(1000);
    $('.btn-contribution').click(function (e) {

        e.preventDefault();

        var $nbreContribution = $(this).parent().parent().parent().prev().find('.nbre-contribution');

        var $contributions=$(this).parent();
        var nombre = $contributions.data('nombre');
        var ckID = 'tacontribution'+nombre;

        var ckContent = CKEDITOR.instances[ckID].getData();

        //var ckContent = CKEDITOR.instances[ckID].destroy();
        var num_utilisateur =$contributions.find('#num-utilisateur').val();
        var num_pensee =$contributions.find('#num-pensee').val();
        var type_contribution =$contributions.find('#type-contribution').val();

        var ckContentTest = CKEDITOR.instances[ckID].getSnapshot();
        var dom=document.createElement("DIV");
        dom.innerHTML=ckContent;
        ckContentTest=(dom.textContent || dom.innerText);
        var contributionID= '#contribution-erreur'+nombre;


        if(pourErreur() && contreErreur() && neutreErreur())
        {

            $(contributionID).show();

        }else{
            $.post('?controller=utilisateur&action=contribuer', {
                num_utilisateur: num_utilisateur,
                num_pensee: num_pensee,
                libelle_contribution: ckContent,
                type_contribution: type_contribution,
                num_page: num_page
            }).done(function (data, textstatus, jqXHR) {
                console.log(data);
                if(data.success==true)
                {
                    $('.td-pensee'+num_pensee).attr("hidden", "hidden ");
                    var mychild=$(data.contribution);
                    var nbreContribution = parseInt($nbreContribution.text());
                    $nbreContribution.text(nbreContribution+1);

                    mychild.find(".btn-comment").click(function(e){
                    //alert();
                    e.preventDefault();

                    var $parentComment=$(this).parent().parent();

                    var num_utilisateur = $parentComment.find('#user_id').val();
                    var num_contribution = $parentComment.find('#contribution_id').val();
                    var parent_num_commentaire = $parentComment.find('#parent_id').val();
                    var libelle_commentaire = $parentComment.find('#libelle').val();

                    $.post('?controller=utilisateur&action=commentaire', {
                        NUM_UTILISATEUR: num_utilisateur,
                        NUM_CONTRIBUTION: num_contribution,
                        LIBELLE_COMMENTAIRE: libelle_commentaire,
                        PARENT_NUM_COMMENTAIRE: parent_num_commentaire,
                        num_page: num_page
                    }).done(function (data, textstatus, jqXHR) {

                        var mycomment = $(data.commentaire);
                        console.log(mycomment.find('.reply'));

                        mycomment.find('.check-btn-commentaire').click(function(e){
                            e.preventDefault();
                            var $this=$(this);

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

                        mycomment.find('.reply').click(function(e){
                            e.preventDefault();

                            var $this = $(this);
                            var contribution_id = $this.data('contribution');
                            var $form = $('#form-reply'+contribution_id);
                            $form.removeAttr("hidden");
                            var parent_id = $this.data('id');
                            var $comment = $('#comment-'+parent_id);

                            $form.find('h4').text('Répondre à ce commentaire')
                            $form.find('#parent_id').val(parent_id);
                            $comment.after($form);
                        });

                        $parentComment.after(mycomment);
                        $('.livecomment'+data.num_commentaire).hide().fadeIn(800);



                    }).fail(function (jqXHR, textstatus, errorThrown) {
                        console.log(jqXHR);
                    });


                });



                    mychild.find('.check-btn-contribution').click(function(e){
                        e.preventDefault();
                        var $this=$(this);
                        $(this).removeClass('is-checked');
                        $.post('?controller=utilisateur&action=like', {
                            ref:$(this).data('ref'),
                            num_ref:$(this).data('num_ref'),
                            num_utilisateur:$(this).data('num_utilisateur'),
                            check_contribution : 1,
                            num_page: num_page
                        }).done(function(data, textstatus, jqXHR){
                            console.log(data);

                            var nblike=$this.find("#check-count");
                            nblike.html(data.NOMBRE_LIKE_CONTRIBUTION);
                            $this.removeClass('is-checked');
                            if(data.success) {
                                $this.addClass('is-checked');
                            }

                        }).fail(function(jqXHR, textstatus, errorThrown){
                            console.log(jqXHR);
                        });
                    });



                    $contributions.parent().parent().after(mychild);


                    $('.my'+data.num_contribution).hide().fadeIn(800);

                    mychild.find('.small-Ed2').click(function(e){
                        e.preventDefault();
                        //alert();
                        var numContribtion = $(this).data('num_ref');

                        var $commentBox = $('.comment-box'+numContribtion);
                        $commentBox.removeAttr("hidden");

                        countClick++;
                        if(countClick % 2 === 0) $commentBox.attr("hidden", "hidden ");
                    });
                }else{

                }


            }).fail(function (jqXHR, textstatus, errorThrown) {
                console.log(jqXHR);
            });
        }


        function pourErreur()
        {
            if(ckContentTest=='Oui je le pense aussi, autant plus qu' || ckContentTest.match(/^Oui je le pense aussi, autant plus qu(.)/)==null)
            {
                return true;
            }else{
                return false;
            }
        }

        function contreErreur()
        {
            if(ckContentTest=='Non je ne le pense pas. En effet' || ckContentTest.match(/^Non je ne le pense pas. En effet(.)/)==null){
                return true;
            }else{
                return false;
            }
        }

        function neutreErreur()
        {
            if(ckContentTest=='Je ne dirai ni oui ni non. En effet' || ckContentTest.match(/^Je ne dirai ni oui ni non. En effet(.)/)==null)
            {
                return true;
            }else{
                return false;
            }
        }


    })


    $('.closeMe').click(function(e){
        e.preventDefault();

        var $contributions=$(this).parent().parent();
        var nombre = $contributions.data('nombre');
        var ckID = '#contribution-erreur'+nombre;
        $(ckID).hide();
    })

    $('.check-btn-contribution').click(function(e){

        e.preventDefault();
        var $this=$(this);
        $(this).removeClass('is-checked');
        $.post('?controller=utilisateur&action=like', {
            ref:$(this).data('ref'),
            num_ref:$(this).data('num_ref'),
            num_utilisateur:$(this).data('num_utilisateur'),
            check_contribution : 1,
            num_page: num_page
        }).done(function(data, textstatus, jqXHR){
            console.log(data);

            var nblike=$this.find("#check-count");
            nblike.html(data.NOMBRE_LIKE_CONTRIBUTION);
            $this.removeClass('is-checked');
            if(data.success) {
                $this.addClass('is-checked');

            }

        }).fail(function(jqXHR, textstatus, errorThrown){
            console.log(jqXHR);
        });
    });

    $('.link-contribution').click(function(e){
        e.preventDefault();




        var numPensee = $(this).data('num_pensee');

        var $contributionBox = $('.contribution-box'+numPensee);
        $contributionBox.removeAttr("hidden");

        countContribution++;
        if(countContribution % 2 === 0) {
            $contributionBox.attr("hidden", "hidden ");
            $('.comment-hide').attr("hidden", "hidden ");
        }

    });


})