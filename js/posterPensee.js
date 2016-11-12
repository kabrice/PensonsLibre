$(document).ready(function() {



    if(window.location.hash) {
        $("#messageContent").append("<p>success</p>");
    }

    //removeMessenger();

    $('#btn-Ed2').click(function (e) {

        e.preventDefault();
        var pensee = $('#text-Ed').val();
        var explication = CKEDITOR.instances.textareaEd.getData();
        var photo = $('#file-photo').val();

        var explicationTest = CKEDITOR.instances.textareaEd.getSnapshot();
        var dom=document.createElement("DIV");
        dom.innerHTML=explication;
        explicationTest=(dom.textContent || dom.innerText);


       // removeMessenger();


        if(pensee=='Pensez vous aussi qu' || pensee.match(/^Pensez vous aussi qu(.)*\?/)==null || pensee.length>160)
        {
            $('#pensee-erreur').removeAttr("hidden");
        }else if(explicationTest.match(/^En effet(.*)Je propose/)==null){
            $('#explication-erreur').removeAttr("hidden");
        }else if(photo==undefined){
            $('#photo-erreur').removeAttr("hidden");
        }else{
            $.post('?controller=utilisateur&action=posterpensee', {
                textarea: pensee,
                textareaEd: explication,
                photo: photo
            }).done(function (data, textstatus, jqXHR) {
                if(data.success){

                    $.cookie("poster", 1);
                    location.reload();
                    $('#pensee-success').removeAttr("hidden");
                } else

                $('#pensee-exist').removeAttr("hidden");
            }).fail(function (jqXHR, textstatus, errorThrown) {
                console.log(jqXHR);
            });
        }



    })



    if( $.cookie("poster")==1) //if($.cookie("reload")==1)
    {

        $.cookie('poster', null);

        $('html, body').animate({
            scrollTop: $(".pensee-libelle").offset().top
        }, 1000);

    }

    $('.bcontribution-alert').click(function(e){
        e.preventDefault();
        var $parent = $(this).parent().parent();
        $parent.attr("hidden","hidden");
    })

})