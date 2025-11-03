<?php

namespace Apikor;

use Vosiz\Enums\Enum as Enum;

class EngineExceptionCodeEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'general'                       => 0x0000,
            // setup                        => 0x1###
            // -install                     => 0x11##
            'setup-install-redirect-loop'   => 0x1100
        ];
        self::AddValues($vals);
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

class ContainerException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class DbException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class EngineWorkException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class FakupException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class FatalErrorException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class FileException extends \Exceptionf {

    /**
     * File not found - static constructor
     * @param string $path
     */
    public static function FileNotFound(string $path) {

        return new FileException("File not found on path: %s", $path);
    }

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }

}

class NotImplementedYetException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $msg, ...$args) {

        return parent::__construct("Functionality is not implemented yet (contact dev team) ".$msg, ...$args);
    }
}

class RedirectException extends \Exception {

    /**
     * Contructor - format based
     */
    public function __construct(string $msg = 'not found', int $code) {

        return parent::__construct("Redirection exception detected: ".$msg, $code);
    }
}

class OutputFormatterException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $formatter, string $msg) {

        return parent::__construct("Output formatter ($formatter) error: ".$msg);
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
