<?php
namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

    use \F3il\Field;
    use F3il\Form;
use F3il\Request;

class Nouveauutilisateurform extends Form{

    public function __construct($destination, $mode=Form::EDIT_MODE) {
        parent::__construct($destination, $mode);
        $this->addFormField(new Field('email', 'Email','',true, FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL));
        $this->addFormField(new Field('motdepasse', 'Mot de passe','',true, FILTER_SANITIZE_STRING));
        $this->addFormField(new Field('confirmation', 'Confirmation','',true, FILTER_SANITIZE_STRING));
        $this->addFormField(new Field('NUM_ANNEE_ETUDE', 'NUM_ANNEE_ETUDE','',true, FILTER_SANITIZE_NUMBER_INT));
    }


    public function render() {
        if(count($this->_messages) > 0):
            ?>
            <div class="alert alert-danger"><?php  echo $this->_messages[0]['message']; ?> </div>
            <?php
        endif;
        $bienvenue=Request::get('bienvenue');
        if(\F3il\Messenger::hasMessage() && $bienvenue==='inscription'):
            ?>
           <?php //$function = new FunctionsHelper();
            //echo $function->sweet();?>
        <div class="alert alert-danger"><?php echo \F3il\Messenger::getMessage(); ?></div>
        <?php endif; ?>
        <form class="form-signin" method="POST" action="<?php echo $this->_destination; ?>">

            <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email 3iL (exemple@3il.fr)" value="<?php  htmlspecialchars($this->_fields['email']->value);?>" required autofocus>
            <div class="input-append">
                <input type="password" id="inputPassword" name="motdepasse" class="form-control" placeholder="Mot de passe" value="<?php htmlspecialchars($this->_fields['motdepasse']->value);?>" required size="1">
                <input type="password" id="inputPassword" name="confirmation" class="form-control" placeholder="Confirme ton mot de passe :)" value="<?php htmlspecialchars($this->_fields['confirmation']->value);?>" required size="1">
                <select class="form-control" name="NUM_ANNEE_ETUDE" required>
                    <?php 
                      $sh=new SelectHelper("Choisis ton année d'étude",'annee_etude','num_annee_etude', 'libelle_annee_etude');
                      $sh->generate();

                    ?>
                </select>
                <input type="hidden" name="action" value="inscription" />
                <?php \F3il\CsrfHelper::csrf(); ?>
                <button class="btn btn-lg btn-primary btn-block btn-signin" id="sign2" type="submit"> Inscription</button>
                </span>

            </div>

        </form>

        <?php
    }


    public function motdepasseValidate(){
        $valid = strlen($this->_fields['motdepasse']->value) >= 6;

        if(!$valid){
            $this->addMessage ('motdepasse', 'Trop court, 6 carac mini');
        }
        return $valid;
    }




    public function confirmationValidate(){
        $valid = $this->_fields['confirmation']->value == $this->_fields['motdepasse']->value;

        if(!$valid){
            $this->addMessage ('confirmation', 'Confirmation ne correspond pas');
        }
        return $valid;
    }

    public function validerEmail3IL()
    {
        $email = filter_var($this->_fields['email']->value,FILTER_VALIDATE_EMAIL);
        $modele="/@3il\.fr$/";
        $valid=preg_match($modele, $email, $tab);
        if(!$valid)
        {

            $this->addMessage ('email', 'L\'email n\'est pas de la forme exemple<strong>@3il.fr</strong> :(');

        }
        return $valid;
    }

    public function _editValidate() {
        $valid = filter_var($this->_fields['email']->value,FILTER_VALIDATE_EMAIL);

        if(!empty($this->_fields['motdepasse']->value) || !empty($this->_fields['confirmation']->value)){
            $valid = $valid && $this->motdepasseValidate() && $this->confirmationValidate() && $this->validerEmail3IL();
        }

        return $valid;
    }




}
