<?php

namespace Apikor;

// req commons
define("COMMONS", __DIR__."/commons");
require_once(COMMONS."/unique.php");
require_once(COMMONS."/user.php");

class Commons {

    /**
     * Require once - safe
     * @param string $path Path to file to require
     * @throws FileException
     */
    public static function Require(string $path) {

        if (!file_exists($path)) {
            throw FileException::FileNotFound($path);
        }

        require_once($path);
    }

}