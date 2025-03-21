<?php

namespace Apikor;

require_once(__DIR__.'/inc.php');

use Apikor\EngineConfigurator as Configurator;
use Apikor\EngineDiagnostics as Diag;
use Apikor\Tools\UrlParser;
use Vosiz\Enums\Enum as Enum;

// LIST TO_DO
// ==============
// call action
// get response
// get output

class EngineRunModeEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'normal'    => 0x00,
            'diagnose'  => 0x10,
        ];
        self::AddValues($vals);
    } 
}


class Engine {

    private $Configurator;  public function GetConfigurator() { return $this->Configurator; }
    private $Diags;
    private $Parser;

    private $Mode;

    /**
     * Constructor
     */
    public function __construct(EngineRunModeEnum $mode) {

        $this->Configurator = new Configurator();
        $this->Diags        = new Diag($this);
        $this->Parser       = UrlParser::Create();
        
        // default config
        $this->DefaultConfig();   
        
        $this->Mode = $mode;
        
        Diag::Info("Engine started...");
    }

    /**
     * Add config to pool
     * @param EngineConfig $cfg Configuration
     */
    public function SetupConfig(EngineConfig $cfg) {

        $this->Configurator->AddConfig($cfg);

        Diag::Debug("Added config: %s", $cfg->__toString());
    }

    /**
     * Run engine
     * @return null|string response or error (NULL)
     */
    public function Work() {

        Diag::Info("Working...");

        $errs = $this->Parser->CheckRequired();
        if($errs !== NULL) {

            Diag::Fatal("Parser URL parts are not defined");
            Diag::Warning('Awaited format: .../v(version)/(format)/(module)/(controller)/(action)?...');
            foreach($errs as $part => $e) {

                Diag::Error("- %s: %s", $part, $e);
            }

            return NULL;
        }
    }

    /**
     * Runs diagnostics
     */
    public function Diagnose() {

        if(!$this->Mode->Compare(EngineRunModeEnum::GetEnum('diagnose')))
            return;

        Diag::Info("Running diagnose...");
        $this->Diags->PrintConfigs();
        $this->Diags->PrintMessages();

        if($this->Configurator->Compare('diagnostics', 'print_urlparser', true)) {

            Diag::Debug("Running diagnose...");
            $this->Parser->PrintMe();
        }
        
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
                'print_urlparser'   => false,
            ],

        ];

        $configs = EngineConfig::ToConfigBulk($defcfg);
        foreach($configs as $cfg) {

            $this->SetupConfig($cfg);
        }
    }
}