<?php

namespace Apikor;

require_once(__DIR__.'/inc.php');

use Vosiz\Enums\Enum;
use Vosiz\Utils\Collections\Dictionary;

use Apikor\Engine\EngineModeEnum;
use Apikor\Engine\Diagnostics;
use Apikor\Engine\EngineStatus;
use Apikor\Engine\Container;
use Apikor\Engine\Config;

use Apikor\Tools\UrlParser;
use Apikor\Tools\DbConnection;
use Apikor\Output\Formatter;
use Apikor\Response\Header;
use Apikor\Response\Response;
use Apikor\Response\ResponseFactory;
use Apikor\Engine\NotFoundException;
use Apikor\Engine\ForbiddenException;
use Apikor\Engine\BadRequestException;
use Vosiz\VaTools\Db\DbConnectionConfig;
use Vosiz\VaTools\Structure\Credentials;


final class Engine extends Singleton {

    private $Status;
    private $Logger;
    private $Diags;
    private $Container;  public function GetContainer() { return $this->Container; }
    private $Config;     public function GetConfig()    { return $this->Config;    }
    private $Problems = [];

    private $Initialized = false;
    private EngineModeEnum $Mode;


    public function __construct(bool $prod = TRUE, bool $debug = FALSE) {

        $this->RegisterInstance();

        try {

            // sets basics — Logger must be first (EngineStatus depends on it)
            $this->Logger    = new Logger();
            $this->Status    = new EngineStatus();
            $this->Diags     = new Diagnostics();
            $this->Container = new Container();
            $this->Config    = new Config();

            // engine defaults
            $this->DefaultConfig();
            if(!$prod) {

                $this->Mode = EngineModeEnum::GetEnum($debug ? 'diag' : 'dev');
            }

        } catch(\Exception $exc) {

            $this->Exception('Engine.Constructor', $exc, "FATAL");
        }
        
    }


    /**
     * Starts engine
     * @throws ApikorException
     */
    public function Start() {

        try {

            $this->Config->Validate();

        } catch (Engine\ConfigException $exc) {

            // invalid user config — reset to defaults, continue as leak
            $this->DefaultConfig();
            $this->Problems[] = sprintf("Config reset to defaults: %s", $exc->getMessage());
            $this->Logger->Warn("Engine.Start: %s", end($this->Problems));
        }

        try {

            $this->Initialized = true;
            $this->Status->Start();

        } catch (\Exception $exc) {

            $this->Exception("Engine.Start", $exc, "Engine start failed fatally");
        }

    }

    /**
     * Redirects to URL and exits
     * @param string $url Target URL
     * @param int $code HTTP redirect code
     */
    public function Redirect(string $url, int $code = Header::HTTP_REDIRECT) {

        \http_response_code($code);
        \header("Location: $url");
        exit;
    }

    /**
     * Sets up database connection from config file and registers it in container
     * @param string $cfg_path Path to db.cfg
     * @return Engine
     */
    public function SetupDb(string $cfg_path) {

        try {

            $cfg  = read_cfg($cfg_path);
            $dsn  = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $cfg['MYSQL_HOST'], $cfg['MYSQL_DB']);
            $creds = new Credentials($cfg['MYSQL_USER'], $cfg['MYSQL_PASS']);
            $db   = new DbConnection(new DbConnectionConfig($dsn, $creds));
            $this->Container->Register('db', $db);
            $this->Logger->Info("Engine.SetupDb: connected to %s/%s", $cfg['MYSQL_HOST'], $cfg['MYSQL_DB']);

        } catch(\Exception $exc) {

            $this->Exception("Engine.SetupDb", $exc, "DB setup failed");
        }

        return $this;
    }

    /**
     * Processes request and sends response
     */
    public function Work() {

        try {

            if(!$this->Initialized)
                throw new Engine\EngineException("Not initialized (call Start before Work)");

            $this->Status->Work();

            $formatter = Formatter::Resolve($this->Config->Get('format.default', 'xml')); // fallback before URL is parsed

            // parse URL
            $parser = UrlParser::Create();
            $errors = $parser->CheckRequired();
            if($errors !== null)
                throw new BadRequestException("URL parsing failed: %s", implode(', ', $errors));

            $formatter = Formatter::Resolve($parser->GetFormat());

            // load and instantiate controller
            $module     = $parser->GetModule();
            $controller = $parser->GetController();
            $action     = camel($parser->GetAction());

            $ctrl_path = sprintf('%s/modules/controllers/%s/%s.php', __DIR__, $module, $controller);
            if(!file_exists($ctrl_path))
                throw new NotFoundException("Controller not found: %s/%s", $module, $controller);

            require_once($ctrl_path);

            $cls = sprintf('Apikor\\Modules\\%sModule\\%sController', ucfirst(camel($module)), ucfirst(camel($controller)));
            if(!class_exists($cls))
                throw new NotFoundException("Controller class not found: %s", $cls);

            $ctrl_instance = new $cls();

            if(!method_exists($ctrl_instance, $action))
                throw new NotFoundException("Action not found: %s::%s", $cls, $action);

            // call action
            $result   = $ctrl_instance->$action();
            $response = ResponseFactory::Ok($result, $parser);

            $this->Status->Finish($this->Problems);

            echo $formatter->Format($response);

        } catch(BadRequestException $exc) {

            echo $formatter->Format(ResponseFactory::BadRequest($exc->getMessage(), $parser ?? null));

        } catch(NotFoundException $exc) {

            echo $formatter->Format(ResponseFactory::NotFound($exc->getMessage(), $parser ?? null));

        } catch(ForbiddenException $exc) {

            echo $formatter->Format(ResponseFactory::Forbidden($exc->getMessage(), $parser ?? null));

        } catch(\Exception $exc) {

            $this->Exception("Engine.Work", $exc, "Engine failed fatally");
        }

    }

    /**
     * Handles exception
     * @param string $origin Origin message
     * @param Exception $exc Cought exception
     * @return ApikorException 
     */
    public function Exception(string $origin, \Exception $exc, string $fmt, ...$args) {

        $aexc = new ApikorException($origin, $fmt, ...$args);
        $excf = new \Exceptionf($exc->getMessage());
        $aexc->SetInner($excf);
        $this->HandleError($aexc);
        return $aexc;
    }

    /**
     * Handles exception (creates new)
     * @param string $origin Origin message
     * @return ApikorException 
     */
    public function Exceptionf(string $origin, string $fmt, ...$args) {

        $aexc = new ApikorException($origin, $fmt, ...$args);
        $this->HandleError($aexc);
        return $aexc;
    }


    /**
     * Configures engine with user settings
     * @param array $cfg [key => value]
     * @return Engine
     */
    public function Setup(array $cfg) {

        $this->Config->SetBulk($cfg);
        return $this;
    }

    /**
     * Sets engine defaults
     */
    private function DefaultConfig() {

        try {

            $this->Mode = EngineModeEnum::GetEnum('prod');
            $this->Config->Set('format.default', 'xml');

        } catch(\Exception $exc) {

            $this->Exception("Engine.DefaultConfig", $exc, "Default config setup failed");
        }

    }
    

    // TODO: popere se s chybou
    private function HandleError(\Exception $exc) {

        // log exception
        $this->Logger->Exc("%s", $exc->__toString());

        // debug mode dump
        if($this->Mode->IsDebug()) {
            
            $this->Logger->Dump();
            $this->Print($exc);
            echo 'Diagnostics:</br>';
            foreach($this->Diags->Full() as $row) {
                echo sprintf("%s\r\n", $row);
            }
        }
    }

    // TODO: vypise
    private function Print(...$args) {

        foreach($args as $a) {

            echo "<pre>";
            echo $a;
            echo "</pre>";
            debug($a);
        }
        
    }

}