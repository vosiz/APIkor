<?php

try {

    // Essentials
    require_once(__DIR__.'/functions.php');
    require_once(__DIR__.'/exc.php');
    require_once(__DIR__.'/singleton.php');
    require_once(__DIR__.'/logger.php');

    // tools
    require_once(__DIR__.'/tools/tools.php');

    // Engine
    IncludeFolder('engine', [
        'exc',
        'status',
        'mode',
        'diagnose',
        'container'
    ]);

    // Core
    IncludeFolder('core', ['controller']);

    // Models
    IncludeFolder('core/models/base', [
        'model',
        'dbmodel'
    ]);
    IncludeFolder('core/models', ['user']);

    // Services
    IncludeFolder('core/services/base', [
        'service',
        'dataservice',
        'dbservice'
    ]);
    IncludeFolder('core/services', ['users']);

    // Output
    IncludeFolder('output', ['formatter']);
    IncludeFolder('output/formats', ['var', 'xml']);

    // Response
    IncludeFolder('response', [
        'header',
        'payload',
        'types',
        'response',
        'factory'
    ]);

    // Mappers
    IncludeFolder('core/mappers/base', [
        'mapper',
        'dbmapper'
    ]);
    IncludeFolder('core/mappers/base/db', [
        'entity_dbmapper',
        'enum_dbmapper'
    ]);

} catch(\Exception $exc) {

    dump($exc->ToString());
}



/**
 * Includes folder to APIkor
 * @param string $path Path to folder (no leading or trailing '/')
 * @param array $files List of files (only names)
 */
function IncludeFolder(string $path, array $files = []) {

    $base = __DIR__;
    foreach($files as $f) {

        require_once(sprintf("%s/%s/%s.php", __DIR__, $path, $f));
    }
}