/**
 * Created by Edgar on 07/09/2016.
 */

$(document).ready(function()
{
    var num_utilisateur = $(".vote").data('num_utilisateur');


    $("#notificationLink").click(function()
    {

        $("#notificationContainer").fadeToggle(300);
        $("#notification_count").fadeOut("slow");

        var num_pensee = $(".notif").data("pensee");
        var num_contribution = $(".notif").data("contribution");
        var num_commentaire = $(".notif").data("commentaire");
        var num_code = $(".notif").data("code");

        $.post('?controller=utilisateur&action=posterpensee', {
            num_utilisateur:num_utilisateur,
            num_pensee: num_pensee,
            num_contribution: num_contribution,
            num_commentaire: num_commentaire,
            num_code: num_code,
            maj_vu:true
        }).done(function (data, textstatus, jqXHR) {
            if(data.success){
                console.log(data);
            }

        }).fail(function (jqXHR, textstatus, errorThrown) {
            console.log(jqXHR);
        });

        return false;
    });

//Document Click hiding the popup
    $(document).click(function()
    {
        $("#notificationContainer").hide();
    });

//Popup on click
    $("#notificationContainer").click(function()
    {
        //e.stopPropagation();
    });
    //var num_utilisateur = data.num_utilisateur;



      // setInterval( function() {
            $.post('?controller=utilisateur&action=posterpensee', {
                num_utilisateur: num_utilisateur,
                notification: true
            }).done(function (data, textstatus, jqXHR) {
                if (data.success) {
                    console.log(data);

                    $("#notifications-ul").html("");
                    $("#notifications-ul").append(data.notifications);

                    if (data.notification_count != 0) $("#notification_count").removeAttr("hidden");
                    console.log(data.notification_count);

                    $("#notification_count").html("");
                    $("#notification_count").append(data.notification_count);

                    $(".notif").click(function () {
                        var num_pensee = $(this).data("pensee");
                        var num_contribution = $(this).data("contribution");
                        var num_commentaire = $(this).data("commentaire");
                        var num_code = $(this).data("code");
                        $.cookie("pensee", num_pensee);
                        $.cookie("contribution", num_contribution);
                        $.cookie("commentaire", num_commentaire);
                        $.cookie("code", num_code);


                        //Envoyer les données de maj sur lu
                        $.post('?controller=utilisateur&action=posterpensee', {
                            num_utilisateur: num_utilisateur,
                            num_pensee: num_pensee,
                            num_contribution: num_contribution,
                            num_commentaire: num_commentaire,
                            num_code: num_code,
                            maj_lu: true
                        }).done(function (data, textstatus, jqXHR) {
                            if (data.success) {
                                console.log(data);

                            }

                        }).fail(function (jqXHR, textstatus, errorThrown) {
                            console.log(jqXHR);
                        });
                        //Fin Envoie

                    })

                }

            }).fail(function (jqXHR, textstatus, errorThrown) {
                console.log(jqXHR);
            });
        //}, 1000);

    if($.cookie("pensee") != null && $.cookie("contribution")){
        var num_contribution = $.cookie("contribution");
        var num_pensee = $.cookie("pensee");
        var num_commentaire = $.cookie("commentaire");
        var num_code = $.cookie("code");

        if(num_code == 11 || num_code == 4 || num_code == 5){

            scroll(".pensee"+num_pensee);

        }else if(num_code == 1 || num_code == 22 || num_code == 44){

            $(".contribution-box"+num_pensee).removeAttr("hidden");
            scroll("#mycontibution"+num_contribution);

        }else if(num_code == 2 || num_code == 221 || num_code == 3 || num_code == 33 || num_code == 321 || num_code == 444){

            $(".contribution-box"+num_pensee).removeAttr("hidden");
            $(".comment-box"+num_contribution).removeAttr("hidden");
            scroll("#comment-"+num_commentaire);

        }

        $.cookie('pensee', null);
        $.cookie('contribution', null);
        $.cookie('commentaire', null);
        $.cookie('code', null);

    }

    function scroll(item){
        $('html, body').animate({
            scrollTop: $(item).offset().top
        }, 1000);

    }

});