<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

use \F3il\Field;
use \F3il\Form;
use F3il\Request;

class RecoverForm extends Form {

    public function __construct($destination, $mode = Form::CREATE_MODE) {
        parent::__construct($destination, $mode);

        $this->addFormField(new Field('email', 'Email','',true, FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL));
        $this->addFormField(new Field('motdepasse', 'Mot de passe','',true, FILTER_SANITIZE_STRING));
    }

    public function render() {
        if(count($this->_messages) > 0):
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php  echo $this->_messages[0]['message']; ?>
            </div>
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
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Adresse email" value="<?php echo htmlspecialchars($this->_fields['email']->value); ?>" autocomplete="on" />
            </div>
            <div class="form-group">
                <div class="row">

                    <div class="col-lg-6 col-sm-6 col-xs-6">
                        <input type="submit" name="cancel_submit" id="cancel-submit" tabindex="2" class="form-control btn btn-danger" value="Annuler" />
                    </div>
                    <div class="col-lg-6 col-sm-6 col-xs-6">
                        <input type="submit" name="recover-submit" id="recover-submit" tabindex="2" class="form-control btn btn-success" value="M'envoyer le mail de recuperation" />
                    </div>


                </div>
            </div>
            <input type="hidden" class="hide" name="token" id="token" value="<?php

            $funct = new \Pensonslibre\FunctionsHelper();
            $token = $funct->token_generator();
            echo $token; ?>">
            <?php \F3il\CsrfHelper::csrf(); ?>
        </form>
        <?php
    }
}


