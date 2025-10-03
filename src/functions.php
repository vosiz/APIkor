<?php

if(!function_exists('fatal')) {

    function fatal(string $fmt, ...$args) {

        throw new \Apikor\FatalErrorException("[FATAL] ".$fmt, ...$args);
    }
}

if(!function_exists('fakup')) {

    function fakup(string $fmt, ...$args) {

        throw new \Apikor\FakupException("[FAKUP] ".$fmt, ...$args);
    }
}

if(!function_exists('dump')) {

    function dump(...$args) {

        foreach($args as $a) {

            echo '<pre>';
            print_r($a);
            echo '</pre>';
        }
        
    }
}