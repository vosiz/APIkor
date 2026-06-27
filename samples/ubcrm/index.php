<?php

namespace UserBasedCRM;

// Required constants - apikor subdomain name and API keyword
// URL format: <host>/<SUBDOMAIN_NAME>/<API_URL_KEYWORD>/v<version>/<format>/<module>/<controller>/<action>
define('SUBDOMAIN_NAME', 'apikor');
define('API_URL_KEYWORD', 'api');

require_once(__DIR__.'/../../src/engine.php');

use Apikor\Engine;

// Create engine instance
// Parameters: production mode (false = dev), debug mode (true = debug output)
$engine = new Engine(FALSE, true);

// Set engine configuration
// 'format.default' - fallback output format when not specified in URL
$engine->Setup([
    'format.default' => 'pre'
]);

// Connect to database using config file
$engine->SetupDb(__DIR__.'/db.cfg');

// Start engine - validates configuration, initializes internals
$engine->Start();

// Process request and send response
$engine->Work();
