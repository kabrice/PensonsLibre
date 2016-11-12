<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

use \F3il\Field;
use \F3il\Form;
use F3il\Request;

class ResetForm extends Form {

    public function __construct($destination, $mode = Form::CREATE_MODE) {
        parent::__construct($destination, $mode);
        $this->addFormField(new Field('password', 'password','',true, FILTER_SANITIZE_STRING));
        $this->addFormField(new Field('confirm_password', 'confirm_password','',true, FILTER_SANITIZE_STRING));
    }

    public function render() {
        if(count($this->_messages) > 0):
            ?>
            <div class="alert alert-danger"><?php  echo $this->_messages[0]['message']; ?> </div>
            <?php
        endif;
        if(\F3il\Messenger::hasMessage()):
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo \F3il\Messenger::getMessage(); ?>
            </div>
        <?php endif; ?>
        <form id="register-form"  method="post" role="form" autocomplete="off">

            <div class="form-group">
                <input type="password" name="password" id="password" tabindex="1" class="form-control" value="<?php htmlspecialchars($this->_fields['password']->value);?>" placeholder="Entre ton nouveau mot de passe" required/>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" id="confirm-password" tabindex="1" class="form-control" class="form-control" value="<?php htmlspecialchars($this->_fields['confirm_password']->value);?>" placeholder="Confirme ce nouveau mot de passe" required/>
            </div>
            <div class="form-group">
                <div class="row">

                    <div class="col-sm-6 col-sm-offset-3">
                        <input type="submit" name="reset-submit" id="reset-submit" tabindex="4"  class="form-control btn btn-success " value="Continuer" />
                    </div>

                </div>
            </div>
            <input type="hidden" class="hide" name="token" id="token" value="<?php
            $funct = new \Pensonslibre\FunctionsHelper();
            $token = $funct->token_generator();
            echo $token; ?>">
        </form>
        </form>
        <?php
    }
}


