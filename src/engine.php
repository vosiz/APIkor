<?php

namespace Apikor;

require_once(__DIR__.'/config.php');
require_once(__DIR__.'/diagnose.php');

use Apikor\EngineConfigurator as Configurator;
use Apikor\EngineDiagnostics as Diagnostics;



// TODOLIST
// ==============
// config 
// parse url
// call action
// get response
// get output



class Engine {

    private $Configurator;  public function GetConfigurator() { return $this->Configurator; }
    private $Diags;

    /**
     * Constructor
     */
    public function __construct() {

        $this->Configurator = new Configurator();
        $this->Diags = new Diagnostics($this);

        // default config
        $this->DefaultConfig();
        
    }

    /**
     * Add config to pool
     * @param EngineConfig $cfg Configuration
     */
    public function SetupConfig(EngineConfig $cfg) {

        $this->Configurator->AddConfig($cfg);
    }

    /**
     * 
     */
    public function Work() {

    }

    /**
     * Runs diagnostics
     */
    public function Diagnose() {

        $this->Diags->PrintConfigs();
        $this->Diags->PrintMessages();
    }


    /**
     * Default configurations 
    */
    private function DefaultConfig() {

        $defcfg = [

            // diagnostics
            'diagnostics' => [
                'level'             => EngineDiagnosticsLevelEnum::GetEnum('error'),
                'print_messages'    => false,
                'print_configs'     => false,
            ],

        ];

        $configs = EngineConfig::ToConfigBulk($defcfg);
        foreach($configs as $cfg) {

            $this->SetupConfig($cfg);
        }
    }
}