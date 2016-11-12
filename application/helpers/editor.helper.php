<?php
namespace Pensonslibre;
defined('__PENSONSLIBRE__') or die('Acces Interdit');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author Edgar
 */
class EditorHelper {
    //put your code here

    public static function render($id){
        
       return "
         <textarea id='$id' name='$id'></textarea>";
      
    }
    public static function header(){
        return "<link rel='stylesheet' href='css/jquery.cleditor.css' />";
    }
    public static function footer($id){
        return "<script type='text/javascript' src='js/jquery-2.1.4.js'></script>
    <script type='text/javascript' src='js/jquery.cleditor.js'></script>
    <script src='js/jquery-1.11.3.min.js' type='text/javascript'></script>
    <script type='text/javascript' src='js/jquery.cleditor.min.js'></script>"
        . "<script>$(document).ready(function () { $('#$id').cleditor(); });</script>";   
    }
   
}