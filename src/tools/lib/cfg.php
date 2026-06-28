<?php

/**
 * Reads KEY=VALUE config file
 * @param string $path
 * @return array
 * @throws \Exceptionf
 */
function read_cfg(string $path) {

    if(!file_exists($path))
        throw new \Exceptionf("Config file not found: %s", $path);

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $cfg = [];
    foreach($lines as $line) {

        if(strpos($line, '#') === 0) continue;
        if(strpos($line, '=') === false) continue;

        [$key, $val] = explode('=', $line, 2);
        $cfg[trim($key)] = trim($val);
    }

    return $cfg;
}
