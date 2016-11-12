<?php
/**
 * Created by IntelliJ IDEA.
 * User: Edgar
 * Date: 01/05/2016
 * Time: 18:55
 */

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $error = '';
    $img = '';
    $dir = 'uploads/';
    $extensions = array("jpeg","jpg","png");
    foreach($_FILES['img_file']['tmp_name'] as $key => $tmp_name )
    {
        $file_name = $_FILES['img_file']['name'][$key];
        $file_size =$_FILES['img_file']['size'][$key];
        $file_tmp =$_FILES['img_file']['tmp_name'][$key];
        $file_type=$_FILES['img_file']['type'][$key];
        $file_ext = strtolower(end(explode('.',$file_name)));
        if(in_array($file_ext,$extensions ) === true)
        {
            if(move_uploaded_file($file_tmp, $dir.$file_name))
            {
                $img .= '<div class="col-sm-2"><div class="thumbnail">';
                $img .= '<img src="'.$dir.$file_name.'" />';
                $img .= '</div></div>';
            }
            else
                $error = 'Error in uploading few files. Some files couldn\'t be uploaded.';
        }
        else
        {
            $error = 'Error in uploading few files. File type is not allowed.';
        }
    }
    echo (json_encode(array('error' => $error, 'img' => $img)));
}
die();
?>