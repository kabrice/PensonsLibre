<?php
namespace F3il;
defined('__F3IL__') or die('Acces Interdit');

abstract class Controller{
    protected $defaultActionName;
/**
 * Spécifie le nom de l'actiom à utiliser
 * @param type $actionName
 */    
Public function setDefaultActionName($actionName){
        $method= $actionName.'Action';
        if(method_exists($this, $method)){
            $this->defaultActionName=$actionName;
        }
        else{
            throw  new Error("pas d'action $method\n");
        }
}
/**
 * permettra de lire la valeur d'une propriété
 */
public function  getDefaultActionName(){
    return $this->defaultActionName;
}
/**
 * Spécifie le nom de l'actiom à utiliser
 * @param type $actionName
 */
public function execute($actionName){
   // $this->loadTemplateData();
    $method= $actionName.'Action';
    if(method_exists($this, $method)){
        $this->$method();
    }
    else{
        throw new \F3il\Error("pas de method $method\n");
    }
}
/*
 public function loadTemplateData() {
        $page = Page::getInstance();
        $pagemodel = new \Suivitr\EnseignantsModel();
        $page->listerparTypeSujet = $pagemodel->listerparTypeSujet();
        $page->listerparTypeEleve = $pagemodel->listerparTypeEleve();
    }*/
}
