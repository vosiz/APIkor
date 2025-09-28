<?php

namespace Apikor\Output;

class VarDumpFormat extends Formatter {

    public function Format($data) {
        
        var_dump($data);
    }
}