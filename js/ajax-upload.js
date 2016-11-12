// You can modify the upload files to pdf's, docs etc
//Currently it will upload only images
$(document).ready(function($) {


    // Function to show messages
    function ajax_msg(status, msg) {
        var the_msg = '<div class="alert alert-' + (status ? 'success' : 'danger') + '">';
        the_msg += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        the_msg += msg;
        the_msg += '</div>';
        $(the_msg).insertBefore($('.uploadlogo').parent());
    }

    // Upload btn
    $(".uploadlogo").change(function() {
        readURL(this);
    });

    function readURL(input) {
        var url = input.value;
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "gif" || ext == "svg")) {
            var path = $('.uploadlogo').val();
            var filename = path.replace(/^.*\\/, "");
            $('.fileUpload span').html('Chargement réussi : ' + filename);
            // console.log(filename);
            var formData = new FormData();
            //formData.append('any_var', 'any value');
            /*for (var i = 0; i < input.files.length; i++) {*/
            //formData.append('img_file_' + i, files[i]);
            formData.append('img_file[]', input.files[0]);

            /*}*/
            $.ajax({
                url: "upload.php", // Change name according to your php script to handle uploading on server
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
                error: function(request) {
                    ajax_msg(false, request.status+' An error has occured while uploading photo. '+request.responseText);
                },
                success: function(json) {
                    var v=$('<input type="hidden">');
                    $(v).attr("name","photo");
                    $(v).attr("id","file-photo");
                    $(v).attr("value",'uploads/'+filename);
                    $('#file-photo').remove();
                    $('#photopreview').remove();
                    $('.fileUpload').append(v);
                    //$('.head').hide();
                    $('<img id="photopreview"  style="display:block;max-width:30%;max-height:30%" src="uploads/'+filename+'">').insertBefore(".fileUpload");
                    $('#photopreview').click(function(){
                        $(".uploadlogo").trigger("click");
                    });
                }
            });
        } else {
            $(".uploadlogo").val("");
            $('.fileUpload span').html('Only Images Are Allowed!');
        }
    }

});
