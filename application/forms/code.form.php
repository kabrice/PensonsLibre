<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

use \F3il\Field;
use \F3il\Form;
use F3il\Request;

class CodeForm extends Form {

    public function __construct($destination, $mode = Form::CREATE_MODE) {
        parent::__construct($destination, $mode);
        $this->addFormField(new Field('code', 'code','',true, FILTER_SANITIZE_STRING));
    }

    public function render() {
        if(count($this->_messages) > 0):
            ?>
            <div class="alert alert-danger"><?php  echo $this->_messages[0]['message']; ?> </div>
            <?php
        endif;
        $bienvenue=Request::get('bienvenue');
        if(\F3il\Messenger::hasMessage() && $bienvenue=='connexion'):
            ?>
            <div class="alert alert-danger"><?php echo \F3il\Messenger::getMessage(); ?></div>
        <?php endif; ?>
        <form id="register-form"  method="post" role="form" autocomplete="off">

            <div class="form-group">

                <input type="text" name="code" id="code" tabindex="1" class="form-control" placeholder="####################" value="<?php echo htmlspecialchars($this->_fields['code']->value); ?>" autocomplete="off" required/>
            </div>
            <div class="form-group">
                <div class="row">

                    <div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-3 col-sm-offset-2  col-xs-6">
                        <input type="submit" name="code" id="code-cancel" tabindex="2" class="form-control btn btn-danger" value="Cancel" />

                    </div>
                    <div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-3 col-sm-offset-2 col-xs-6">
                        <input type="submit" name="code-submit" id="recover-submit" tabindex="2" class="form-control btn btn-success" value="Continue" />

                    </div>

                </div>
            </div>
            <input type="hidden" class="hide" name="token" id="token" value="<?php
            $funct = new \Pensonslibre\FunctionsHelper();
            $token = $funct->token_generator();
            echo $token; ?>">
        </form>
        <?php
    }
}


