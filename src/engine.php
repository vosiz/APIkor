<?php

namespace Apikor;

require_once(__DIR__.'/inc.php');

use Apikor\EngineConfigurator as Configurator;
use Apikor\EngineDiagnostics as Diag;
use Apikor\EngineInstall;
use Apikor\Response;
use Apikor\Output\Formatter;
use Apikor\Db\DbConMySql;
use Apikor\Tools\UrlParser;
use Apikor\Tools as Tools;
use Apikor\Commons;
use Vosiz\Enums\Enum;
use Vosiz\Utils\Collections\Dictionary;


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

    const DB_DEF_KEY = 'main';

    const REDIRECT_INSTALL = 'system/install/base';

    private static $Singleton = null;

    private $Configurator;      public function GetConfigurator()   { return $this->Configurator;   }
    private $Diags;
    private $DataProvider;      public function GetDataProvider()   { return $this->DataProvider;   }
    private $DataContainer; 
    private $Parser;
    private $Install;
    private $LocalConfig;       public function GetLocalConfig()    { return $this->LocalConfig;    }
    private $Mode;
    private $Errors;
    private $Status;


    /** 
     * Return singleton instance
     * @return \Apikor\Engine
     * @throws \Exception
     */
    public static function GetSingleton() {

        try {

            if(self::$Singleton == null)
                throw new FakupException("Get singleton was called before engine inst. Applogic!");

            return self::$Singleton;

        } catch(\FakupException $exc) {

            throw new \Exception($exc);
        }
    }

    /** 
     * Provides data stored in container
     * @param EngineContainerSectionEnum $section From which section (section key) - returns all
     * @param string $key From which assoc. part of section
     * @return \Apikor\DataProvider
     * @throws \Apikor\EngineWorkException
    */
    public static function ProvideData(EngineContainerSectionEnum $section, string $key = null) {

        try {
            
            return self::GetSingleton()->GetDataProvider()->GetData($section, $key);

        } catch(\Exception $exc) {

            $msg = sprintf("Engine.DataProvide failure (%s), section=%s%s", $exc->getMessage(), $section, is_null($key) ? '' : '.'.$key);
            throw new EngineWorkException($msg);
        }

    }

    /**
     * Gets main database connection
     * @return \Apikor\DbProvider
     * @throws \Exception
     */
    public static function GetMainDbConn() {

        try {

            return self::ProvideData(EngineContainerSectionEnum::GetEnum('db'), self::DB_DEF_KEY);

        } catch(\Exception $exc) {

            throw $exc;
        }
        
    }
    

    /**
     * Constructor
     */
    public function __construct(EngineRunModeEnum $mode) {

        try {

            if(self::$Singleton == null)
                self::$Singleton = $this;

            $this->Status = EngineStatusEnum::GetEnum('cold');
            $this->Errors = new Dictionary();
            
            $this->Mode = $mode;

        } catch (\Exception $exc) {

            $this->Errors->Add("Creation failed: ".$exc->getMessage());
            throw new \Exceptionf("Cannot create Apikor/Engine instance: ".$exc->getMessage());
        }
    
    }


    /**
     * Starts Engine - init basically
     * @throws \Exception
     */
    public function Start() {

        try {

            $this->Configurator     = new Configurator();
            $this->Diags            = new Diag($this);
            $this->Parser           = UrlParser::Create();
            $this->DataContainer    = new EngineDataContainer();
            $this->DataProvider     = new EngineDataProvider($this->DataContainer);
            $this->Install          = new EngineInstall();
            $this->LocalConfig      = new LocalConfig();

            // default config
            $this->DefaultConfig();   

            Diag::Info("Engine started...");
            $this->Status = EngineStatusEnum::GetEnum('started');

        } catch(\Exception $exc) {

            $this->Errors->Add("Starts failed: ".$exc->getMessage());
            throw new \Exceptionf("Cannot start Apikor/Engine: %s", $exc->getMessage());
        }

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
     * Loads local config file at path
     * @param string $path If '' use configurator
     * @throws \ConfigException
     */
    public function LoadLocalConfig(string $path = '') {

        try {

            $this->LocalConfig->SetPath($path);
            $this->LocalConfig->Parse();

        } catch(\Exception $exc) {

            throw new ConfigException("Cannot load config: %s", $exc->getMessage());
        }
        
    }

    /**
     * Run engine
     * @return null|string response or error (NULL)
     */
    public function Work() {

        try {

            Diag::Info("Working...");
            $this->Status = EngineStatusEnum::GetEnum('cold');
            $this->DataProvider->LoadEntities();

            // config check
            $this->ConfigCheck();

            // parsing check
            $this->ParsingCheck();

            // actual work
            $response = $this->Respond();
            $output = $this->Format($response);
            $this->Status = EngineStatusEnum::GetEnum('done');
            return $output;

        } 
        catch(FakupException $exc) {

            $this->Status = EngineStatusEnum::GetEnum('broken');
            $this->Errors->Add("EngineWorkFK: ".$exc->getMessage());
            $response = Response\Response::Create(\Apikor\Response\Message::CreateRetval(retval_fakup($exc->ToString())));
            $output = $this->Format($response);
            // debug($fup_response);
            return $output;
        }
        catch(FatalErrorException $exc) {

            $this->Status = EngineStatusEnum::GetEnum('broken');
            $this->Errors->Add("EngineWorkFE: ".$exc->getMessage());
            $response = Response\Response::Create(\Apikor\Response\Message::CreateRetval(retval_fatal($exc->ToString())));
            $output = $this->Format($response);
            // debug($fup_response);
            return $output;
        }
        catch (\Exception $exc) {

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
     * Connect to Database (note you can connect to multiple databases) 
     * @param string $connection_string Connection string
     * @param string $user Username
     * @param string $pass Password
     * @param string $key Connection ID
    */
    public function DbConnect(string $connection_string, string $user, string $pass, $key = 'main', bool $is_main = true) {

        try {

            $conn = new DbConMySql($connection_string, $user, $pass);
            $this->DbConns[$key] = $conn;
            $this->DataProvider->SetData(EngineContainerSectionEnum::GetEnum('db'), $key, $conn);
            Diag::Info("Connected to DB as '$key'");
            if($is_main) {

                // sets duplicate to 'main'
                if($key !== self::DB_DEF_KEY) {
                    $this->DataProvider->SetData(EngineContainerSectionEnum::GetEnum('db'), self::DB_DEF_KEY, $conn);
                }
                Diag::Info("Is MAIN connection");
            }

        } catch(DbException $exc) {

            throw $exc;
        }
        catch(\Exception $exc) {

            throw $exc;
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
     * Exception handle
     * @param \Exception $exc
     * @param bool $production
     * @throws \Exception
     */
    public function Exception(\Exception $exc, bool $production = false) {

        $this->Log('exc', $exc->getMessage());
        try {

            if($this->TryResolveException($exc))
                return;

        } catch(RedirectException $exc) {

            if($exc->getCode() == EngineExceptionCodeEnum::GetEnum('setup-install-redirect-loop')->GetValue()) {

                $this->Install->MainDatabase();
                return;
            }

            throw new \UnimplementedStateException($exc->getCode());

        } catch(\Exception $exc) {

            throw $exc;
        }
        
        // proper exception handling
        if(!$production) {

            [$status, $desc, $data] = $this->CheckStatus();
            echo sprintf("Engine state '%s' (%s)", $status->GetKey(), $desc);
            echo sprintf("<br>Data (%d): <br>", count($data));
            foreach($data as $str) {
    
                echo "- [ERROR] ".$str."<br>";
            }
            echo sprintf("[Exception] (%s): %s<br>", typeof($exc), $exc->getMessage());
            echo "<pre>";
            echo sprintf("Stack: %s", $exc->getTraceAsString());
            echo "</pre>";
    
            $this->Diagnose();

        } else {

            $this->Diagnose->Exc();
        }
    }

    /**
     * Logs message
     * @param string $level
     * @param string $fmt formatted string
     * @throws \Exception
     */
    public function Log(string $level, string $fmt, ...$args) {

        try {

            // enum by level
            $enum = EngineDiagnosticsLevelEnum::GetEnum($level);
        
            // TODO: logger

            $this->Diags->CreateMessage($enum, $fmt, ...$args);

        } catch(\Exception $exc) {
            
            $this->Exception($exc, defined(PRODUCTION));
        }
        
    }

    /**
     * Logs multiple messages
     * @param array $msgs Messages, key is level
     */
    public function Logm(array $msgs = []) {

        foreach($msgs as $level => $msg) {

            $this->Log($level, $msg);
        }
    }

    /**
     * Redirects to url
     * @param string $custom_url application part
     * @return false|true if true cyclic redirection
     */
    public function Redirect(string $custom_url) {

        $apikor_url = $this->Parser->GetRedirectUrl($custom_url);

        if ($_SERVER['REQUEST_URI'] === $apikor_url)
            return true;
        
        header("Location: $apikor_url", true, 302);
        exit;
    }

    /**
     * Installs apikor
     * @return bool true if installed successfully
     * @throws \Exception
     */
    public function Install() {

        try {

            if(!$this->Install->MainDatabase())
                return false;

        } catch(\Exception $exc) {

            throw $exc;
        }

        return true;
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
     * Check configuration
     * @return false
     * @throws EngineWorkException
     */
    private function ConfigCheck() {

        $errs = [];

        try {

            // TODO: own controllers
            // controllers path
            // if($this->Configurator->GetConfig('paths', 'controllers') === NULL) {

            //     Diag::Error("Controllers paths not configured");
            // }

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
     * Creates reponse on request
     * @return Response\Response
     * @throws EngineWorkException
     */
    private function Respond() {

        try {

            $module_name        = $this->Parser->GetModule();
            $controller_name    = $this->Parser->GetController();
            $action_name        = $this->Parser->GetAction();
            $version            = $this->Parser->GetVersion();
            $pars               = $this->Parser->GetParameters();

            // figure module
            $user_module_path = Tools\FILEOPS_PathCombine($this->Configurator->GetConfig('paths', 'modules'));
            if(empty($user_module_path) || !\file_exists($user_module_path)) {
                
                // TODO: is custom module path needed?
                throw new ConfigException("User path to Modules is not setup");
            }
            $full_user_module_path = Tools\FILEOPS_PathCombine($user_module_path, $module_name);

            // // module/controller
            // list($type, $controller_path) = Tools\FILEOPS_Exists(
            //     $full_user_module_path,
            //     Tools\FILEOPS_PathCombine(Configurator::MODULES_PATH, $module_name), 
            //     $controller_name);

            // if($type == 'user') {

            //     $appns = $this->Configurator->GetConfig('ns', 'app');
            //     $cls = sprintf("%s\%sModule\%sController", $appns, ucfirst($module_name), ucfirst($controller_name));

            // } else if($type == 'master') {
                
            //     $cls = sprintf("Apikor\%sModule\%sController", ucfirst($module_name), ucfirst($controller_name));

            // } else {

            //     throw new ConfigException("Unknown Module path return type: %s", $type);
            // }
            // Commons::Require($controller_path);
            // Diag::Debug('Controller path at \'%s\'', $controller_path);
            // $controller = new $cls($pars);
            $controller = self::ProvideData(EngineContainerSectionEnum::GetEnum('controller'), $controller_name);

            // action call - get message
            $message = $this->CallAction($controller, camel($action_name), intval($version));
            if($message === null || !$message || !($message instanceof Response\Message)) {

                throw new \Exception("Message is null, empty or not Message");
            }

            // response creation (wrapping)
            return Response\Response::Create($message);

        } catch (\Apikor\FakupException $exc) { // dev fakup

            throw $exc;

        } catch (\Apikor\FatalErrorException $exc) {

            throw $exc;

        } catch (\Exception $exc) { // others

            throw new EngineWorkException("Response failed: %s", $exc->getMessage());
        }
        
    }

    /**
     * Formats response to desired format
     */
    private function Format($response) {

        try {

            $format = $this->Parser->GetFormat();
            Diag::Debug("Will format to '$format'");
            $formatter = Formatter::Translate($format);

            return $formatter->Format($response);

        } catch (\Exception $exc) {

            throw new EngineWorkException("Formatting failed: %s", $exc->getMessage());
        }

    }

    /**
     * Finds and call action
     * @param Controller $controller Controller
     * @param string $action Action to trigger
     * @param int $version Action version
     * @return mixed
     * @throws Exception
     */
    private function CallAction(Controller $controller, string $action, int $version) {

        try {

            // action serach and call
            $action = $controller->FindAction($action, $version);
            Diag::Debug("Found action '$action' in controller '%s'", $controller->__toString());   
            $defs = $controller->ActionCheck($action);        
            Diag::Debug("Found %d definitions rules for '$action'", count($defs));
            $controller->ApplyActionRules($defs);    
            return $controller->{$action}();

        } catch (\Exception $exc) {

            throw $exc;            
        }
        
    }

    /**
     * Try resolve if true exception
     * @param \Exception $exc
     * @throws RedirectException
     * @throws \Exception
     */
    private function TryResolveException(\Exception $exc) {

        try {

            if($exc instanceof \Vosiz\VaTools\Db\DbNotFoundException) {

                if($exc->getCode() == 1049) {
    
                    // redirect to install
                    if($this->Redirect(self::REDIRECT_INSTALL))
                        throw 
                            new RedirectException(
                                "Multiple redirections to ".self::REDIRECT_INSTALL, 
                                EngineExceptionCodeEnum::GetEnum('setup-install-redirect-loop')->GetValue());
                }
            }

        } catch(\Exception $exc) {

            throw $exc;
        }

    }

}