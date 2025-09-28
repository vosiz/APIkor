<?php

namespace Apikor\Output;

class VarBroswerFormat extends Formatter {

    public function Format($data) {

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}