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
class SelectHelper {
    private $table; // nom de la table ou on prend les données
    private $valueField;
    private $displayField;
    private $filtre=array();
    private $emptyoption;
    
    /**
     * 
     * @param type $table
     * @param type $valueField
     * @param type $displayField
     * constructeur du helper
     */
    public function __construct($optionVide,$table,$valueField,$displayField=null) {
        $this->table=$table;
        $this->valueField=$valueField;
        $this->displayField=$displayField==null?$valueField:$displayField;
        $this->emptyoption=$optionVide;
    }
    
    /**
     * 
     * @param type $filtre
     * permet d'ajouter un filtre
     */
    public function addfilter($filtre){
        $this->filtre[]=$filtre;
    }
    /**
     * recupère les données du helper
     * @return array()
     * @throws \F3il\Error
     */
    public function getData(){
        $db = \F3il\Application::getDB();
        $queryfilter="";
        foreach($this->filtre as $filtre){
            $queryfilter=$queryfilter===""?"$queryfilter and $filtre":"$filtre";
        }
        $queryfilter=$queryfilter===""?1:$queryfilter;
        $sql = "SELECT ".$this->valueField.", ".$this->displayField
            . " FROM ".$this->table
            . " WHERE $queryfilter";
        $req = $db->prepare($sql);
        try {
            $req->execute();
        } catch (\PDOException $ex) {
            throw new \F3il\Error("Erreur SQL " . $ex->getMessage());
        }

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function generate(){
       $datas = $this->getData();
       ?>
       <option value=""> <?php echo $this->emptyoption; ?> </option>
       <?php
       foreach ($datas as $data){
           ?>
       <option value="<?php echo $data[$this->valueField]; ?>"><?php echo $data[$this->displayField]; ?></option>
        <?php
       }
    }
}
