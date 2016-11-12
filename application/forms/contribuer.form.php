<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

use \F3il\Field;
use \F3il\Form;

class ContribuerForm extends Form {

    public function __construct($destination, $mode = Form::CREATE_MODE) {
        parent::__construct($destination, $mode);

         $this->addFormField(new Field('NUM_UTILISATEUR', 'NUM_UTILISATEUR','',true, FILTER_SANITIZE_STRING));
        $this->addFormField(new Field('NUM_PENSEE', 'NUM_PENSEE','',true, FILTER_SANITIZE_STRING));
           $this->addFormField(new Field('TYPE_CONTRIBUTION', 'TYPE_CONTRIBUTION','',true, FILTER_SANITIZE_STRING));
    }

    public function render() {
        if(count($this->_messages) > 0):
            ?>
            <div class="alert alert-danger"><?php  echo $this->_messages[0]['message']; ?> </div>
            <?php
        endif;
        if(\F3il\Messenger::hasMessage()):
        ?>
            <div class="alert alert-danger"><?php echo \F3il\Messenger::getMessage(); ?></div>
        <?php endif; ?>


                    <input id="num-utilisateur" type="hidden" name="NUM_UTILISATEUR" value="<?=$this->numuser?>">
                    <input id="num-pensee" type="hidden" name="NUM_PENSEE" value="<?=$this->numpensee?>">
                    <input id="type-contribution" type="hidden" name="TYPE_CONTRIBUTION" value="<?=$this->typec?>">




                 <?php \F3il\CsrfHelper::csrf(); ?>
        <?php
    }
}


