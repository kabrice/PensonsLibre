<?php
namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

use \F3il\Field;
use F3il\Form;

class SuggestionForm extends Form
{

    public function __construct($destination, $mode = Form::EDIT_MODE)
    {
        parent::__construct($destination, $mode);
        $this->addFormField(new Field('textarea', 'textarea', '', true, FILTER_SANITIZE_STRING));
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
        <div id="msgedgar">
        <?php if(isset($_POST['textarea'])){
            $messenger = new Messenger_edgarHelper("Données envoyées avec succès. Merci :)", "success");
            echo $messenger->messenger();
        }?>
        </div>
        <form method="POST" action="?controller=profil&action=suggestion">

            <textarea class="form-control" name="textarea" id="textarea" rows="10" placeholder="Que suggères-tu ? As-tu une question ?" value="<?php echo htmlspecialchars($this->_fields['textarea']->value); ?>" required autofocus></textarea>
            <button class="btn btn-lg btn-primary btn-block" id="btn-Ed4" type="submit"> Valider</button>
        </form>

        <?php
    }


    public function textareaValidate(){
        $valid_suggest = strlen($this->_fields['textarea']->value) >= 50;


        if(!$valid_suggest){
            $this->addMessage ('textarea', 'texte rop court, 50 caractères minimum');
        }
        return $valid_suggest;
    }

}