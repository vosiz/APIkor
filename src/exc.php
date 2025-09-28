<?php

namespace Apikor;


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

class NotImplementedYet extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $msg = '') {

        return parent::__construct("Functionality is not implemented yet (contact dev team) %s", $msg);
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
