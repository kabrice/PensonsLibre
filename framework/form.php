<?php
namespace F3il;

defined('__F3IL__') or die('Acces Interdit');

abstract class Form{
    const CREATE_MODE=1;
    const EDIT_MODE=2;
    protected $_fields=[];
    protected $_valid;
    protected $_messages=[];
    protected $_mode;
    protected $_destination;


    /**
     * 
     * @param type $mode
     */
    public function __construct($destination,$mode=Form::CREATE_MODE) {
        $this->_mode=$mode;
        $this->_destination=$destination;
        
    }
    
    /**
     * 
     * @param type $name
     * @return type
     * @throws \F3il\Error
     */
    public function __get($name) {
        if(isset($this->_fields[$name])){
            return $this->_fields[$name] ;
        }
        else {
            throw new \F3il\Error ('Champ non defini');
        }
        
    }
    
    /**
     * 
     * @param type $name
     */
    public function __isset($name) {
        if(isset($this->_fields[$name])){
          return true; 
        }
        else {
            return false;
        }
    }
    
    /**
     * 
     * @param \F3il\Field $field
     * @throws \F3il\Error
     */
    protected function addFormField(Field $field){
        if (array_key_exists($field->name,$this->_fields)){
            
            throw new \F3il\Error ("Champ ".$field->name,$this->_fields." deja defini");
        }
        $this->_fields[$field->name]= $field;
    }
    
    /**
     * 
     * @param type $nom
     * @param type $message
     */
    public function addMessage($nom,$message){
        $this->_messages[]=['name'=>$nom,'message'=>$message];
        
        
    }
    
    /**
     * 
     * @return type
     */
    public function _createValidate(){
         $valid = true;
        
        foreach($this->_fields as $F){
            
            if($F->required && empty($F->value)){
                $valid = false;
                $this->addMessage($F->name, 'Erreur sur le champ '.$F->label);
            }
            
            if(method_exists($this, $F->name."Validate")){
                $meth = $F->name."Validate";
                $fieldValid = $this->$meth();
            }
            else if(!empty($F->phpValidator)){
                if(!filter_var($F, $F->phpValidator))
                        $fieldValid = true;
            }
            else{
                $fieldValid = $this->defaultValidator($F);
            }
            
            $valid = $valid && $fieldValid;
        }
        
        $this->_valid = $valid;
        return $valid;  
    }
    
    
    /**
     * 
     * @param type $nom
     * @return boolean
     */
    public function defaultValidator($nom){
        return true;
        
    }
    
    /**
     * 
     * @return type
     */
    public function _editValidate(){
        return $this->_createValidate();
       
    }
    
    /**
     * 
     */
    public function getData(){
    foreach ($this->_fields as $key=>$valeur){
        
        if(\F3il\Request::isPost()){
            $valeur->value= \F3il\Request::post($key);
        }
        else {
            $valeur->value= \F3il\Request::get($key);
        }
    }
    return $this->_fields;
   }
    
    /**
     * 
     * @return type
     */
    public function getMessages(){
        return $this->_messages;
        
    }
    
    /**
     * 
     * @param array $source
     */
    public function loadData(array $source){
        foreach($this->_fields as $F){
            if(isset($source[$F->name]) && !empty($source[$F->name])){
                $val = $source[$F->name];
            }
            else{
                $val = $F->defaultValue;
            }
            
            $meth = $F->name.'Filter';
            
            if(method_exists($this, $meth)){
                $F->value = $this->$meth($val);
            }
            else if(!empty($F->phpFilter)){
                $F->value = filter_var($val, $F->phpFilter);
            }
            else{
                $F->value = $val;
            }
        }
        
    }
    
    /**
     * 
     */
   abstract  public  function render();
        
    /**
     * lance la validation en fonction du mode choisi 
     * @return type
     * @throws \F3il\Error
     */
    public function validate(){
        if($this->_mode == Form::CREATE_MODE){
            $this->_valid = $this->_createValidate();
        }
        elseif($this->_mode == Form::EDIT_MODE) {
            $this->_valid = $this->_editValidate();
           
        }
         else {
            throw new \F3il\Error ("Mode de formulaire inconnu");
        }
        return $this->_valid;
        
    }
    
  
}
