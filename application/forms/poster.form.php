<?php
namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

use \F3il\Field;
use F3il\Form;

class PosterForm extends Form{

    public function __construct($destination, $mode=Form::EDIT_MODE) {
        parent::__construct($destination, $mode);
        $this->addFormField(new Field('textarea', 'textarea','',true, FILTER_SANITIZE_STRING));
        $this->addFormField(new Field('textareaEd', 'textareaEd','',true, FILTER_SANITIZE_STRING));
        $this->addFormField(new Field('photo', 'photo','',true, FILTER_SANITIZE_STRING));
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
        <div id='pensee-success' class="messenger pensee-success" hidden="hidden">
            <?php
                $messenger = new Messenger_edgarHelper("Cool :) ta pensée vient d'être publiée. N'hésite pas à la partager sur <strong>Facebook avec
                                                        des 3iliens</strong> pour savoir s'ils sont d'accord avec toi ;-)", "success");
                echo $messenger->messenger();
            ?>
        </div>
        <div id='pensee-exist' class="messenger pensee-exist" hidden="hidden">
            <?php
            $messenger = new Messenger_edgarHelper("Désolé :( cette pensée existe déjà, il faut une nouvelle ;-)", "danger");
            echo $messenger->messenger();
            ?>
        </div>

        <div id='pensee-erreur' class="messenger pensee-erreur" hidden="hidden">
            <?php
                $messenger = new Messenger_edgarHelper('La pensée n\'a pas pu être soumise :(<br/>&nbsp;
                                           -> Verifies que ta pensée est sous la forme <strong><em>"Pensez vous aussi  qu...?".</em></strong><br/>
                                              <em>Tu peux voir des exemples sur les pensées déjà postées.</em> ;-)', "danger");
                echo $messenger->messenger();
            ?>
        </div>

        <div id='explication-erreur' class="messenger explication-erreur" hidden="hidden">
            <?php
                $messenger = new Messenger_edgarHelper('L\'explication n\'a pas pu être soumise :(<br/>&nbsp;
                                           -> Verifies que ton explication contient au moins 2 paragraphes (saut de 2 lignes) commençant respectivement par les expressions
                                              <strong><em>"En effet"</em></strong> et <strong><em>"Je proprose".</em></strong><br/>&nbsp;
                                              <em>Tu peux voir des exemples sur les pensées déjà postées.</em> ;-)', "danger");
                echo $messenger->messenger();
            ?>
        </div>
        <div id='photo-erreur' class="messenger photo-erreur" hidden="hidden">
            <?php
                $messenger = new Messenger_edgarHelper('L\'image n\'a pas pu être transmise :(<br/>&nbsp;
                                           -> <strong>Vérifies bien que tu as uploadé une image.</strong><br/>&nbsp;
                                              <em>Tu peux voir des exemples sur les pensées déjà postées.</em> ;-)', "danger");
                echo $messenger->messenger();
            ?>
        </div>

            <table>
                <tr><textarea id="text-Ed"  name="textarea" placeholder="Pensez vous aussi que" value="<?php echo htmlspecialchars($this->_fields['textarea']->value); ?>" required autofocus >Pensez vous aussi que</textarea><div id='count-char'>160</div></tr>
                <tr><textarea name="textareaEd" id="textareaEd" value="<?php echo htmlspecialchars($this->_fields['textareaEd']->value); ?>" required autofocus></textarea></tr>
                <tr >
                    <div id="text-Ed2">
                    <center>
                        <div style="margin-top: 10px">
                        <span class="head">Fait glisser une photo ici-bas</span>
                        </div>
                        <div class="fileUpload blue-btn btn">
                            <span>ou cliquer ici </span>
                            <input type="file" class="uploadlogo"  name="photo" accept="image/*"/>
                        </div>
                    </center>
                    </div>
                </tr>
                <!--<tr>


                    <div class="row" style="width: 580px;margin-left: 0px;">

                        <div class="img-zone text-center" id="img-zone">
                            <div class="img-drop">
                                <h2><small>Fait glisser une photo ici :)</small></h2>
                                <h5><em>- ou si tu préfères -</em></h5>
                                <h2><i class="glyphicon glyphicon-camera"></i></h2>
                                            <span class="btn btn-primary btn-file" id="btn-Ed">
                                                Sélectionne les photos sur l'ordinateur<input name="photo" type="file" id="file-photo" accept="image/*">
                                            </span>
                            </div>
                        </div>
                        <div class="progress hidden">
                            <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-success progress-bar-striped active">
                                <span class="sr-only">0% Complete</span>
                            </div>
                        </div>

                    </div>
                    <div id="img-preview" class="row">

                    </div>
                </tr>-->
                <tr><button class="btn btn-lg btn-primary btn-block" id="btn-Ed2" type="submit"> Poster</button></tr>
                <p id="ck-text"></p>
            </table>


        <?php
    }

}
