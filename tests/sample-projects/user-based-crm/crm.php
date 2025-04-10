<?php

//echo "CRM.PHP";

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


// instantiate engine
$engine = new Engine($engine_mode);

// mandatory configuration
$engine->SetupConfig(EngineConfig::ToConfig('modules', __DIR__.'/crm/modules', 'paths'));

// optional, configuration
$engine->SetupConfig(EngineConfig::ToConfig('print_messages', true, 'diagnostics'));
$engine->SetupConfig(EngineConfig::ToConfig('print_configs', true, 'diagnostics'));
$engine->SetupConfig(EngineConfig::ToConfig('print_urlparser', true, 'diagnostics'));
$engine->SetupConfig(EngineConfig::ToConfig('level', DiagLevelEnum::GetEnum('debug'), 'diagnostics'));


// let it work
try {

    $output = $engine->Work();
    $engine ->Diagnose();

} catch(\Exception $exc) {

    if(!PRODUCTION) {

        [$status, $desc, $data] = $engine->CheckStatus();
        echo sprintf("Engine state '%s' (%s)", $status->GetKey(), $desc);
        echo sprintf("<br>Data (%d): <br>", count($data));
        foreach($data as $str) {

            echo "- [ERROR] ".$str."<br>";
        }
    }
    
    $engine->Diagnose();
}

