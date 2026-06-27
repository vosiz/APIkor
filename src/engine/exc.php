<?php

namespace Apikor\Engine;

class ConfigException extends \Exceptionf {

    /**
     * Constructor, parent-based
     */
    public function __construct(string $fmt, ...$args) {

        parent::__construct($fmt, ...$args);
    }
}

class EngineException extends \Exceptionf {

    /**
     * Constructor
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class NotFoundException extends \Exceptionf {

    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class BadRequestException extends \Exceptionf {

    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class ForbiddenException extends \Exceptionf {

    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}