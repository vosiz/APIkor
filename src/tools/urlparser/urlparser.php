<?php

namespace Apikor\Tools;

use Vosiz\VaTools\Parser\UrlParser      as VaUrlParser;
use Vosiz\VaTools\Parser\UrlStructure   as VaUrlStruct;

if(!defined('SUBDOMAIN_NAME'))
    throw new \Exceptionf("Missing configuration: Define SUBDOMAIN_NAME constant!");

if(!defined('API_URL_KEYWORD'))
    throw new \Exceptionf("Missing configuration: Define API_URL_KEYWORD constant!");

class UrlParser extends VaUrlParser {

    private $Version;           public function GetVersion()    { return $this->Version;    }
    private $Format;            public function GetFormat()     { return $this->Format;     }
    private $Module;            public function GetModule()     { return $this->Module;     }
    private $Controller;        public function GetController() { return $this->Controller; }
    private $Action;            public function GetAction()     { return $this->Action;     }
    private $Parameters;        public function GetParameters() { return $this->Parameters; }


    /**
     * Creates instance
     */
    public static function Create() {

        try {

            // format: <SUB.URL>/<keyword>/v<version>/format/module/part/function?k=v&...
            $parser = new static(VaUrlStruct::Create(SUBDOMAIN_NAME.'/'.API_URL_KEYWORD, array('version', 'format', 'module', 'controller', 'action')));
            $parser->Version    = str_replace('v', '', (string)($parser->GetPartByKey('version')));
            $parser->Format     = $parser->GetPartByKey('format');
            $parser->Module     = $parser->GetPartByKey('module');
            $parser->Controller = $parser->GetPartByKey('controller');
            $parser->Action     = $parser->GetPartByKey('action');
            $parser->Parameters = $parser->Pars;

            return $parser;

        } catch (Exception $exc) {

            throw new UrlException("Cannot parse URL: %s", $exc->getMessage());
        }

    }


    /**
     * Prints variables
     */
    public function PrintMe() {

        echo sprintf("Ver. %s</br>", $this->Version);
        echo sprintf("Out. %s</br>", $this->Format);
        echo sprintf("Mod. %s</br>", $this->Module);
        echo sprintf("Ctr. %s</br>", $this->Controller);
        echo sprintf("Act. %s</br>", $this->Action);
        echo sprintf("Par. (%d) %s</br>", count($this->Parameters), print_r($this->Parameters, true));
    }

    /**
     * Returns last request
     * @param string
     */
    public function GetLastRequest() {

        return $this->FullUrl;
    }

    /**
     * Returns URL
     * @param bool $inc_custom Include custom part
     * @return string
     */
    public function GetUrl(bool $inc_custom = true) {

        if($inc_custom)
            return $this->GetLastRequest();
        else
            return 
                sprintf("%s/v%s/%s", 
                    SUBDOMAIN_NAME.'/'.API_URL_KEYWORD,
                    $this->GetVersion(),
                    $this->GetFormat()
                );
    }

    /**
     * Returns redirect URL part
     * @param string $redirect Custom part
     * @return string
     */
    public function GetRedirectUrl(string $redirect) {

        // base/apikor/vX/format/redirect...
        // returns /apikor
        return 
            sprintf("/%s/v%s/%s/%s",
                API_URL_KEYWORD,
                $this->GetVersion(),
                $this->GetFormat(),
                $redirect
            );
    }

    /**
     * Checks if all requested parts are ok
     * @return null|array errors
     */
    public function CheckRequired() {

        $errors = [];

        if(!($this->Version)) {

            $errors['version'] = "Invalid value (empty)";
        }

        if(!($this->Format)) {

            $errors['format'] = "Invalid value (empty)";
        }

        if(!($this->Module)) {

            $errors['module'] = "Invalid value (empty)";
        }

        if(!($this->Controller)) {

            $errors['controller'] = "Invalid value (empty)";
        }

        if(!($this->Action)) {
            
            $errors['action'] = "Invalid value (empty)";
        }
        
        return empty($errors) ? NULL : $errors;
    }

}