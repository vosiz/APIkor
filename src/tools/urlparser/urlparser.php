<?php

namespace Apikor\Tools;

use Vosiz\VaTools\Parser\UrlParser      as VaUrlParser;
use Vosiz\VaTools\Parser\UrlStructure   as VaUrlStruct;

if(!defined('URL_BASE_PATH'))
    throw new \Exceptionf("Missing configuration: Define URL_BASE_PATH constant!");

if(!defined('API_URL_KEYWORD'))
    throw new \Exceptionf("Missing configuration: Define API_URL_KEYWORD constant!");

class UrlParser extends VaUrlParser {

    private $Version;           public function GetVersion()    { return $this->Version;    }
    private $Format;            public function GetFormat()     { return $this->Format;     }
    private $Module;            public function GetModule()     { return $this->Module;     }
    private $Controller;        public function GetController() { return $this->Controller; }
    private $Action;            public function GetAction()     { return $this->Action;     }
    private $Parameters;        public function GetParameters() { return $this->Parameters; }
    private $Method;            public function GetMethod()     { return $this->Method;     }
    private $Client;            public function GetClient()     { return $this->Client;     }
    private $Ignored;           public function GetIgnored()    { return $this->Ignored;    }
    private $Pattern;           public function GetPattern()    { return $this->Pattern;    }
    private $ApiFound = false;


    /**
     * Creates instance
     * URL format: /<URL_BASE_PATH>/<API_URL_KEYWORD>/<version>/<format>/<module>/<controller>/<action>?params
     * URL_BASE_PATH = path segments before the API keyword (can be empty, can be multi-level: 'projects/apikor').
     * On local:   define('URL_BASE_PATH', 'apikor')  → localhost/apikor/api/v1/...
     * On prod:    define('URL_BASE_PATH', '')         → domain.tld/api/v1/...
     */
    public static function Create() {

        try {

            $keys = ['version', 'format', 'module', 'controller', 'action'];
            $base = implode('/', array_filter([URL_BASE_PATH, API_URL_KEYWORD]));

            $parser = new static(VaUrlStruct::Create($base, $keys));

            // Parse directly from REQUEST_URI — strip URL_BASE_PATH, then API_URL_KEYWORD separately
            $uri = ltrim($_SERVER['REQUEST_URI'] ?? '', '/');

            if(!empty(URL_BASE_PATH)) {
                $base_prefix = URL_BASE_PATH . '/';
                if(strpos($uri, $base_prefix) === 0)
                    $uri = substr($uri, strlen($base_prefix));
            }

            $uri              = rtrim($uri, '/') . '/'; // normalize trailing slash
            $api_prefix       = API_URL_KEYWORD . '/';
            $parser->ApiFound = strpos($uri, $api_prefix) === 0;
            if($parser->ApiFound)
                $uri = substr($uri, strlen($api_prefix));

            $parts = explode('/', explode('?', $uri)[0]);

            $parser->Version    = str_replace('v', '', $parts[0] ?? '');
            $parser->Format     = $parts[1] ?? '';
            $parser->Module     = $parts[2] ?? '';
            $parser->Controller = $parts[3] ?? '';
            $parser->Action     = $parts[4] ?? '';
            $parser->Parameters = $_GET;
            $parser->Method     = $_SERVER['REQUEST_METHOD'] ?? '';
            $parser->Client     = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $parser->Ignored    = array_values(array_filter(explode('/', $base)));
            $parser->Pattern    = implode('/', array_map(fn($k) => '{' . $k . '}', $keys));

            return $parser;

        } catch (\Exception $exc) {

            throw new \Exceptionf("Cannot parse URL: %s", $exc->getMessage());
        }

    }


    /**
     * Returns parsed URL parts — overrides VaUrlParser which uses broken HTTP_HOST-based parsing
     * @return array
     */
    public function GetParts() {

        return [
            'version'    => $this->Version,
            'format'     => $this->Format,
            'module'     => $this->Module,
            'controller' => $this->Controller,
            'action'     => $this->Action,
        ];
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

        $base = implode('/', array_filter([URL_BASE_PATH, API_URL_KEYWORD]));
        return sprintf("%s/v%s/%s", $base, $this->GetVersion(), $this->GetFormat());
    }

    /**
     * Returns redirect URL part
     * @param string $redirect Custom part
     * @return string
     */
    public function GetRedirectUrl(string $redirect) {

        $base = implode('/', array_filter([URL_BASE_PATH, API_URL_KEYWORD]));
        return sprintf("/%s/v%s/%s/%s", $base, $this->GetVersion(), $this->GetFormat(), $redirect);
    }

    /**
     * Checks version and format — missing module/controller/action yields 404 via routing, not 400
     * @return null|array errors
     */
    public function CheckRequired() {

        $errors = [];

        if(!$this->ApiFound) {
            $errors['api'] = sprintf("API keyword '%s' not found in URL", API_URL_KEYWORD);
            return $errors;
        }

        if(!($this->Version))
            $errors['version'] = "Invalid value for 'version' (empty)";

        if(!($this->Format))
            $errors['format'] = "Invalid value for 'format' (empty)";

        return empty($errors) ? NULL : $errors;
    }

}
