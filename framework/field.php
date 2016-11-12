<?php
namespace F3il;

defined('__F3IL__') or die('Acces Interdit');

class Field{
    public $name;
    public $label;
    public $required;
    public $value='';
    public $defaultValue;
    public $phpFilter;
    public $phpValidator;
    /**
     * 
     * @param type $name
     * @param type $label
     * @param type $defaultValue
     * @param type $required
     * @param type $phpFilter de type int pour filter les donnees
     * @param type $phpValidator de type int pour valider les donnees
     */
    public function __construct($name,$label,$defaultValue =null,$required=false,$phpFilter="",$phpValidator="") {
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
//        $this->value = $value;
        $this->defaultValue = $defaultValue;
        $this->phpFilter = $phpFilter;
        $this->phpValidator = $phpValidator;

    }

    
}
