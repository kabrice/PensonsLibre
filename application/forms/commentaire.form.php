<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or define('__PENSONSLIBRE__', '');

use \F3il\Field;
use \F3il\Form;

class CommentaireForm extends Form {

    public function __construct($destination, $mode = Form::CREATE_MODE) {
        parent::__construct($destination, $mode);

        $this->addFormField(new Field('LIBELLE_COMMENTAIRE', 'LIBELLE_COMMENTAIRE','',true, FILTER_SANITIZE_STRING));
         $this->addFormField(new Field('NUM_UTILISATEUR', 'NUM_UTILISATEUR','',true, FILTER_SANITIZE_STRING));
          $this->addFormField(new Field('NUM_CONTRIBUTION', 'NUM_CONTRIBUTION','',true, FILTER_SANITIZE_STRING));
        $this->addFormField(new Field('PARENT_NUM_COMMENTAIRE', 'PARENT_NUM_COMMENTAIRE','',true, FILTER_SANITIZE_STRING));
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
                <textarea placeholder=" Ecris ton commentaire ici :)" rows="1" id="libelle"  name="LIBELLE_COMMENTAIRE" class="col-md-10" required autofocus ><?php echo htmlspecialchars($this->_fields['LIBELLE_COMMENTAIRE']->value); ?></textarea>
                <input type="hidden" name="NUM_UTILISATEUR" value="<?=$this->numuser?>" id="user_id">
                <input type="hidden" name="NUM_CONTRIBUTION" value="<?=$this->numcontribution?>" id="contribution_id">
                <input type="hidden" name="PARENT_NUM_COMMENTAIRE" value="0" id="parent_id">
                <?php \F3il\CsrfHelper::csrf(); ?>

        <?php
    }
}


?>
