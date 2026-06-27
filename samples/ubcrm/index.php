<?php

namespace UserBasedCRM;

define('SUBDOMAIN_NAME', 'apikor');
define('API_URL_KEYWORD', 'api');

require_once(__DIR__.'/../../src/engine.php');

use Apikor\Engine;

$engine = new Engine(FALSE, true);
$engine->SetupDb(__DIR__.'/db.cfg');
$engine->Start();
$engine->Work();
