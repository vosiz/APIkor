<?php

namespace Apikor;

class EngineWorkException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class ConfigException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class UrlException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

// class Fakup extends \AppException {

//     /**
//      * Constructor - string based
//      */
//     public function __construct(string $msg) {

//         return parent::__construct("FAKUP!!!: ".$msg);
//     }
// }