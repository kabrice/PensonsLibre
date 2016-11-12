<?php
namespace Pensonslibre;

defined('__PENSONSLIBRE__') or die('Acces Interdit');

use \F3il\Field;
use F3il\Form;

class DislikeForm extends Form{

    public function __construct($destination, $mode=Form::EDIT_MODE) {
        parent::__construct($destination, $mode);
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
        <form action="<?php echo $this->_destination; ?>" method="POST">
            <button type="submit" class="vote_btn vote_dislike"><i class="fa fa-thumbs-down fa-2x"></i> <?=$apensee["NOMBRE_DISLIKE_PENSEE"]; ?></button>
        </form>
        <?php
    }


}
