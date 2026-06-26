<?php

namespace Apikor\Engine;

use Vosiz\Utils\Collections\Collection;

class Diagnostics {

    private $Messages;

    /**
     * Constructor
     */
    public function __construct() {

        $this->Messages = new Collection;
    }

    // TODO: 
    public function Full() {

        $this->Messages->Clear();

        // system
        $this->System();

        // config

        return $this->Messages->AsArray();
    }

    // TODO: 
    private function System() {

        $this->Messages->Add($this->Header("System"));
    }

    // TODO: 
    private function Header(string $name) {

        return sprintf("<b>%s</b>", $name);
    }
}