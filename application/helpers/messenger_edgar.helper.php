<?php
namespace Pensonslibre;
defined('__PENSONSLIBRE__') or die('Acces Interdit');

class Messenger_edgarHelper{

    public function __construct($message, $color)
    {
        $this->message = $message;
        $this->color = $color;
    }

    function messenger()
    {

        $this->message  = <<<DELIMITER

    <div class="contribution-alert alert alert-$this->color  role="alert">
        <button type="button" class="bcontribution-alert close" ><span aria-hidden="true">&times;</span></button>
        $this->message
     </div>
DELIMITER;

        return $this->message ;
    }
}