<?php

//echo "CRM.PHP";

// TODO LIST
// - number of registered users
// - list of roles + permissions
// - list of user with roles (auth)

namespace SampleProject\UBC\CRM;

define('ROOT', __DIR__.'/../../..');
require_once(__DIR__.'/config.php');
require_once(ROOT.'/src/engine.php');

use Apikor\Engine as Engine;
use Apikor\EngineDiagnostics as Diag;
use Apikor\EngineConfig;
use Apikor\EngineDiagnosticsLevelEnum as DiagLevelEnum;
use Apikor\EngineRunModeEnum as ErmEnum;

// engine configs
$engine_mode = ErmEnum::GetEnum('normal');

if(!PRODUCTION) {

    $engine_mode = ErmEnum::GetEnum('diagnose');
}

// let it work
try {

    // instantiate engine
    $engine = new Engine($engine_mode);
    $engine->Start();

    // mandatory configuration
    $engine->SetupConfig(EngineConfig::ToConfig('modules', __DIR__.'/crm/modules', 'paths'));
    $engine->SetupConfig(EngineConfig::ToConfig('local-config', __DIR__.'/local.cfg', 'paths'));
    $engine->SetupConfig(EngineConfig::ToConfig('app', 'SampleProject\UBC\CRM', 'ns'));

    // optional, configuration
    $engine->SetupConfig(EngineConfig::ToConfig('print_messages', true, 'diagnostics'));
    $engine->SetupConfig(EngineConfig::ToConfig('print_configs', true, 'diagnostics'));
    $engine->SetupConfig(EngineConfig::ToConfig('print_urlparser', true, 'diagnostics'));
    $engine->SetupConfig(EngineConfig::ToConfig('level', DiagLevelEnum::GetEnum('debug'), 'diagnostics'));

    $engine->LoadLocalConfig();
    $engine->DbConnect(DB_CONNECT_STRING, DB_CONNECT_USER, DB_CONNECT_PASS, DB_CONNECT_DBKEY);

    $output = $engine->Work();

} catch(\Exception $exc) {

    try {

        $engine->Exception($exc, PRODUCTION);

    } catch(\Exception $exc) {

        echo "Well done, exception in exception handling... ".$exc->getMessage();
    }
    
}

