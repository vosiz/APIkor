<?php

use Vosiz\Utils\Io;

try {

    // Essentials
    require_once(__DIR__.'/functions.php');
    require_once(__DIR__.'/exc.php');
    require_once(__DIR__.'/singleton.php');
    require_once(__DIR__.'/logger.php');

    // tools
    require_once(__DIR__.'/tools/tools.php');

    // Deploy
    IncludeFiles('deploy', ['dirs', 'deployer']);

    // Engine
    IncludeFiles('engine', [
        'exc',
        'status',
        'mode',
        'diagnose',
        'container',
        'config'
    ]);

    // Core
    IncludeFiles('core', ['controller']);

    // Models
    IncludeFiles('core/models/base', [
        'model',
        'dbmodel'
    ]);
    IncludeDir('core/models');

    // Services
    IncludeFiles('core/services/base', [
        'service',
        'dataservice',
        'dbservice'
    ]);
    IncludeDir('core/services');

    // Output
    IncludeFiles('output', ['formatter']);
    IncludeFiles('output/formats', ['var', 'pre', 'xml']);

    // Response
    IncludeFiles('response', [
        'header',
        'payload',
        'types',
        'response',
        'factory'
    ]);

    // Mappers
    IncludeFiles('core/mappers/base', [
        'mapper',
        'dbmapper'
    ]);
    IncludeFiles('core/mappers/base/db', [
        'entity_dbmapper',
        'enum_dbmapper'
    ]);

} catch(\Exception $exc) {

    dump($exc->ToString());
}



/**
 * Includes list of files from folder
 * @param string $path Path to folder (no leading or trailing '/')
 * @param array $files List of files (only names, no extension)
 */
function IncludeFiles(string $path, array $files = []) {

    Io\Inc::Files(__DIR__ . '/' . $path, $files);
}

/**
 * Includes all PHP files in folder
 * @param string $path Path to folder (no leading or trailing '/')
 */
function IncludeDir(string $path) {

    Io\Inc::Dir(__DIR__ . '/' . $path);
}
