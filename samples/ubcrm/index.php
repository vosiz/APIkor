<?php

namespace UserBasedCRM;

define('SUBDOMAIN_NAME', 'apikor');
define('API_URL_KEYWORD', 'api');

require_once(__DIR__.'/../../src/engine.php');

use Apikor\Engine;

$engine = new Engine(FALSE, true);

// Sample how to get a Engine instance
//$i = Engine::GetInstance();
//echo print_r($i, true);

// Sample how to fire Exception
//$engine->Exceptionf("UBCRM.index", "This is just a sample %s", "EXC");

$engine->Start();
$engine->Work();