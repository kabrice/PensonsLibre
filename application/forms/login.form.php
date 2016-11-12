<?php

namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

use \F3il\Field;
use \F3il\Form;
use F3il\Request;

class LoginForm extends Form {

    public function __construct($destination, $mode = Form::CREATE_MODE) {
        parent::__construct($destination, $mode);

        $this->addFormField(new Field('email', 'Email','',true, FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL));
        $this->addFormField(new Field('motdepasse', 'Mot de passe','',true, FILTER_SANITIZE_STRING));
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
        <form method="post" class="form-signin" autocomplete="off" action="<?php echo $this->_destination; ?>">
                <input type="email" id="inputPassword" name="email" class="form-control" autocomplete="off" placeholder="Email 3iL (exemple@3il.fr)" value="<?php echo htmlspecialchars($this->_fields['email']->value); ?>" required >
            <div class="input-append">
                <input type="password" id="inputPassword" name="motdepasse" class="form-control" placeholder="Mot de passe" value="<?php echo htmlspecialchars($this->_fields['motdepasse']->value); ?>" required>
                <input type="hidden" name="action" value="connection">
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit"> Connexion</button>
                </span>
            </div>
            <?php \F3il\CsrfHelper::csrf(); ?>
        </form>
        <?php
    }
}


