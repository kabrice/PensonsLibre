<?php

namespace F3il;

defined('__F3IL__') or die('Acces Interdit');

class Page {

    private static $_instance;
    protected $template = '';
    protected $view = '';
    protected $data = [];
    protected $scripts = [];
    protected $mycss= [];

    /**
     * Constructeur privÃ©
     */
    private function __construct() {
        
    }

    /**
     * Retourne l'instance de la classe page
     * @return self
     */
    public static function getInstance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new \F3il\Page();
        }
        return self::$_instance;
    }

    /**
     * SpÃ©cifie le nom du template Ã  utiliser
     * @param type $templateName
     */
    public function setTemplate($templateName) {
        defined('APPLICATION_PATH') or die('Application pas definie');
        $chemin = APPLICATION_PATH . "/templates/$templateName.template.php";
        if (file_exists($chemin)) {
            $this->template = $chemin;
        } else {
            throw new \F3il\Error('Fichier de template introuvable');
        }
    }

    /**
     * SpÃ©cifie le nom de la vue Ã  utiliser
     * @param type $viewName
     */
    public function setView($viewName) {
        defined('APPLICATION_PATH') or die('Application pas definie');
        $chemin = APPLICATION_PATH . "/views/$viewName.view.php";
        if (file_exists($chemin)) {
            $this->view = $chemin;
        } else {
            throw new \F3il\Error('Fichier de vue introuvable');
        }
    }

    /**
     * InsÃ¨re la vue dans le template
     */
    private function insertView() {
        if (!empty($this->view)) {
            require $this->view;
        }
    }

    /**
     * DÃ©clenche le rendu du template
     */
    public function render() {
        if (!empty($this->template) && !empty($this->view)) {
            require $this->template;
        }
    }

    /**
     * MÃ©thode magique pour l'ajout de propriÃ©tÃ©
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * MÃ©thode magique pour la lecture d'une propriÃ©tÃ©
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            throw new Error("Propriete " . $name . " introuvable");
        }
    }

    /**
     * MÃ©thode magique pour les tests avec isset
     * @param string $name
     * @return booleane
     */
    public function __isset($name) {
        return isset($this->data[$name]);
    }

    /** 
     * MÃ©thode de transfert d'un tableau vers des propriÃ©tÃ©s
     * @todo Ã©crire le code de la mÃ©thode
     * @param array $data
     */
    public function loadData(array $data) {
        
    }

    public function addScript($nom) {
       $url="js/$nom.js";
        if (is_readable($url)) {
            if (!in_array($url, $this->scripts)) {
                $this->scripts[] = $nom;
            }
        } else {
            throw new Error('fichier script introuvable');
        }
    }

    public function addCss($nom) {
        $url="css/$nom.css";
        if (is_readable($url)) {
            if (!in_array($url, $this->mycss)) {
                $this->mycss[] = $nom;
            }
        } else {
            echo $url;
            throw new Error('fichier css introuvable');
        }
    }
    public function insertScripts() {
        foreach ($this->scripts as $valeur) {
            ?><script src="<?php echo "js/$valeur.js"; ?>" type="text/javascript" ></script>
<?php
        }
    }
       public function insertcss() {
        foreach ($this->mycss as $valeur) {
            ?><link href="<?php echo "css/$valeur.css"; ?>" type="text/css"  rel="stylesheet" />
<?php
        }
    }

}
