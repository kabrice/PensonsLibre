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
class DatagridHelper {
    //put your code here
    protected $colonnes;
    protected $data;
    public  function __construct($data,$colonnes=null,$iscolumn=true){
        if($data!=null){
         if($iscolumn)
        {
            if(is_null($colonnes)){
                     $this->data=  array_values($data);
                    $this->colonnes=  \array_keys($this->data[0]);
                   
            }
            else{
                $this->data=  array_values($data);
                    $this->colonnes=  $colonnes;
                  
            }
        
        }
        else{
            if(is_null($colonnes)){
                $this->data=  array_values($data);
                $this->colonnes= null;
             

            }
            else{
                $this->data=  array_values($data);
                $this->colonnes= $colonnes;
                

            }
        
        }}
        
        
    }
    public function getData() {
        return $this->data;
    }
   public function getColonnes() {
        return $this->colonnes;
    }
    public function setData($param) {
       $this->data=$param;
    }
   public function setColonnes($param) {
        $this->colonnes=$param;
    }
    public  function convert2JSON(){
          if($this->data!=null){
        $tab = \array_map(function($element){
            if(!is_null($element)){
            $chaine= str_replace("'", "-", \array_values(($element)));
           // var_dump($chaine);
           // exit();
            $str="'".\implode("','",$chaine)."'";
            return "["
                . $str
                . "]";
            }
            }, $this->data);
        
        return "[".implode(",",$tab)."]"; 
          }
    }
    public function getTitle(){
       
        $tab = \array_map(function($element){
            return "{title:'"
                . $element
                . "'}";
            }, $this->colonnes);
       return "[".implode(",",$tab)."]";        
    }
    public function render($id){
         if($this->data!=null){
       return "
        <table id='$id' class='display'></table>";
         }
        
    }
    public function header(){
        return "<link href='css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'/>";
    }
    public function footer($id){
         if($this->data!=null){
        return "<script src='js/jquery-1.11.3.min.js' type='text/javascript'></script>
    <script src='js/jquery.dataTables.min.js' type='text/javascript'></script>
    <script>
    dataSet="
    .$this->convert2JSON($id)
    .";
    $(document).ready(function() {
    $('#$id').DataTable( {
        data: dataSet,
        columns:".$this->getTitle()."
        
    } );
} );"
                . "</script>";
    }}
}
