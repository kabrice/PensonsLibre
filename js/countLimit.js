 $('#text-Ed').keyup(function(){

     var textCounter=$('#text-Ed').val().length;
     var pensee = $('#text-Ed').val();

     var debutPensee = 'Pensez vous aussi que';

     if(pensee.match(/^Pensez vous aussi qu(.)*/)==null)
     {

         $('#text-Ed').val(debutPensee.substring(0,debutPensee.length));
     }

     $("#count-char").html(160-textCounter);

     if(textCounter>160)
     {
         var textLimit = $('#text-Ed').val();
         $('#text-Ed').val(textLimit.substring(0,textLimit.length - 1 ));
         $('#btn-Ed2').prop('disabled', true);
     }
     if(textCounter<=160)
     {
         $('#btn-Ed2').prop('disabled', false);
     }
 })

