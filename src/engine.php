<?php

namespace Apikor;

require_once(__DIR__.'/inc.php');

use Apikor\EngineConfigurator as Configurator;
use Apikor\EngineDiagnostics as Diag;
use Apikor\Tools\UrlParser;
use Apikor\Tools as Tools;
use Vosiz\Enums\Enum;
use Vosiz\Utils\Collections\Collection;


class EngineStatusEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'cold'      => 0x00,    // not called Work
            'started'   => 0x10,    // checking, initliazed
            'working'   => 0x11,    // starts to work
            'done'      => 0x12,    // everything ok, work done
            'leak'      => 0x20,    // working, but not ok
            'broken'    => 0x30,    // exception
            'failed'    => 0x31,    // errors
        ];
        self::AddValues($vals);
    } 
}

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
    private $Errors;
    private $Status;

    /**
     * Constructor
     */
    public function __construct(EngineRunModeEnum $mode) {

        try {

            $this->Status = EngineStatusEnum::GetEnum('cold');
            $this->Errors = new Collection();

            $this->Configurator = new Configurator();
            $this->Diags        = new Diag($this);
            $this->Parser       = UrlParser::Create();
            
            // default config
            $this->DefaultConfig();   
            
            $this->Mode = $mode;

        } catch (\Exception $exc) {

            $this->Errors->Add("Starts failed: ".$exc->getMessage());
            throw new \Exceptionf("Cannot create Apikor/Engine instance: ".$exc->getMessage());
        }
        
        Diag::Info("Engine started...");
        $this->Status = EngineStatusEnum::GetEnum('started');
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

        try {

            Diag::Info("Working...");
            $this->Status = EngineStatusEnum::GetEnum('cold');

            // config check
            $this->ConfigCheck();

            // parsing check
            $this->ParsingCheck();

            // actual work
            $response = $this->Respond();
            $output = $this->Format($response);
            $this->Status = EngineStatusEnum::GetEnum('done');
            return $output;

        } catch (\Exception $exc) {

            $this->Status = EngineStatusEnum::GetEnum('broken');
            $this->Errors->Add("EngineWork: ".$exc->getMessage());
            throw $exc;
        }
        
    }

    /**
     * Runs diagnostics
     */
    public function Diagnose() {

        if(!$this->Mode->Compare(EngineRunModeEnum::GetEnum('diagnose')))
            return;

        Diag::Info("Running diagnose...");
        echo "</br>".PHP_EOL;
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

    /**
     * Check engine status
     * @return array[EngineStatusEnum, string, array] data
     */
    public function CheckStatus() {

        $data = [];
        $desc = "";
        switch($this->Status->GetKey()) {

            case 'cold':
                $desc = "Engine not started";
                break;

            case 'started':
                $desc = "Engine started, but not doing any job";
                break;

            case 'working':
                $desc = "Engine is working...";
                break;

            case 'done':
                $desc = "Engine succesfully done work";
                break;

            case 'leak':
                $desc = "Engine is working, but with some issues";
                $data = $this->Errors->ToArray();
                break;

            case 'broken':
                $desc = "Engine broked during work";
                $data = $this->Errors->ToArray();
                break;

            case 'failed':
                $desc = "Engine failed to start";
                $data = $this->Errors->ToArray();
                break;

            default:
                $desc = "Unknown state";
                $data = $this->Errors->ToArray();
                break;
        }

        return [$this->Status, $desc, $data];
    }

    /**
     * Check configuration
     * @return false
     * @throws EngineWorkException
     */
    private function ConfigCheck() {

        $errs = [];

        try {

            // controllers path
            if($this->Configurator->GetConfig('paths', 'controllers') === NULL) {

                Diag::Error("Controllers paths not configured");
            }

        } catch (\Exception $exc) {

            $errs[] = sprintf("Config issue: %s", $exc->getMessage());
        }


        if(!empty($errs)) {

            Diag::Fatal("Engine configuration error");
            $this->Errors->AddRange($errs);

            throw new EngineWorkException("Configuration check failed");
        }
    }

    /**
     * Check parsing URL
     * @return false
     * @throws EngineWorkException
     */
    private function ParsingCheck() {

        $errs = $this->Parser->CheckRequired();
        if($errs !== NULL) {

            $this->Errors->AddRange();
            Diag::Fatal("Parser URL parts are not defined");
            Diag::Warning('Awaited format: .../v(version)/(format)/(module)/(controller)/(action)?...');
            foreach($errs as $part => $e) {

                Diag::Error("- %s: %s", $part, $e);
            }

            $this->Status = EngineStatusEnum::GetEnum('failed');
            throw new EngineWorkException("URL parser check failed");
        }
    }

    /**
     * TODO:
     */
    private function Respond() {

        try {

            $module_name = $this->Parser->GetModule();
            $controller_name = $this->Parser->GetController();
            $action_name = $this->Parser->GetAction();
            $version = $this->Parser->GetVersion();
            $pars = $this->Parser->GetParameters();

            // module/controller
            $controller_path = Tools\FILEOPS_Exists(
                Tools\FILEOPS_PathCombine($this->Configurator->GetConfig('paths', 'modules'), $module_name),
                Tools\FILEOPS_PathCombine(Configurator::MODULES_PATH, $module_name), 
                $controller_name);
            Diag::Debug('Controller path at \'%s\'', $controller_path);
            require_once($controller_path);
            $cls = sprintf("Apikor\%sModule\%sController", ucfirst($module_name), ucfirst($controller_name));
            $controller = new $cls();

            // action call
            $data = $this->CallAction($controller, ucfirst($action_name), intval($version), $pars);

            // response creation
            // create response structure (header, payload... etc)
            return print_r($data, true); //TODO: test

        } catch (\Exception $exc) {

            throw new EngineWorkException("Response failed: %s", $exc->getMessage());
        }
        
    }

    /**
     * TODO:
     */
    private function Format($response) {

        try {

            $format = $this->Parser->GetFormat();
            // TODO: double call??
            //TODO: implement debug with backtracking
            //debug($format);
            debug($response);
            require_once(__DIR__.'/output/formats/'.$format.'.php');
            Diag::Debug("Will format to '$format'");

        } catch (\Exception $exc) {

            throw new EngineWorkException("Formatting failed: %s", $exc->getMessage());
        }

    }


    /**
     * Finds and call action
     * TODO:
     */
    private function CallAction(Controller $controller, string $action, int $version, array $pars = array()) {

        $action = $controller->FindAction($action, $version);
        Diag::Debug("Found action '$action' in controller '%s'", $controller->__toString());
        return $controller->{$action}($pars);
    }
}