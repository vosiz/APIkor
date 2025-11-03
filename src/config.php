<?php

namespace Apikor;

use Vosiz\Enums\Enum as Enum;
use Vosiz\Structure\Flagword as Fword;
use Vosiz\Utils\Collections\Dictionary;

class EngineConfigException extends \Exceptionf {

    /**
     * Constructor, parent-based
     */
    public function __construct(string $fmt, ...$args) {

        parent::__construct($fmt, ...$args);
    }
}

class EngineConfigEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'general'       => 0x00,
            'diagnostics'   => 0x10,
            'paths'         => 0x20,
            'ns'            => 0x21,
        ];
        self::AddValues($vals);
    } 
}

class EngineConfig extends \Keyvalps {

    private $Category;  public function GetCategory() { return $this->Category; }

    /**
     * Constructor
     * @param string $key Identifier
     * @param mixed $value Value
     * @param string $category Category
     */
    public function __construct(string $key, $value, string $category = 'general') {

        if(is_bool($value))
            $value = b2int($value);

        parent::__construct($key, $value);
        $this->Category = $category;
    }

    /**
     * ToString override
     */
    public function __toString() {

        $key = $this->GetKey();
        $val = $this->GetValue();

        return sprintf("%s: %s[%s] = %s", 
            typeof($this), 
            $this->Category, 
            tostr($key), 
            tostr($val));
    }

    /**
     * Converts to Configuration
     * @param string $key Identification
     * @param mixed $value Value
     * @param string $category Category
     * @return EngineConfig
     */
    public static function ToConfig(string $key, $value, string $category = 'general') {

        return new EngineConfig($key, $value, $category);
    }

    /**
     * Converts array of parameters to Configurations
     * @param array $bulk Array[string => array[mixed => mixed]]
     * @return EngineConfig[]
     */
    public static function ToConfigBulk($bulk = array()) {

        $configs = [];
        foreach($bulk as $category => $params){

            foreach($params as $key => $value) {

                $configs[] = self::ToConfig($key, $value, $category);
            }
            
        }

        return $configs;
    }
}

class EngineConfigurator {

    const MODULES_PATH = __DIR__.'/modules';

    private $Configs;   public function GetConfigs() {  return $this->Configs; }

    /** 
     * Constructor
    */
    public function __construct() {

        $this->Configs = new Dictionary();
        foreach(EngineConfigEnum::GetAll()->ToArray() as $enum) {

            $this->Configs->Add(new Dictionary(), $enum->GetKey());
        }
    }

    /**
     * Adds new/update existing config
     * @param EngineConfig $cfg Configuration
     * @throws EngineConfigException
     */
    public function AddConfig(EngineConfig $cfg) {

        try {

            $category = $cfg->GetCategory();
            $clist = $this->Configs->{$category};
            $clist->Add($cfg, NULL, true);

        } catch(Exception $exc) {

            throw new EngineConfigException("Adding configuration failed: ".$exc->getMessage());
        }
    }

    /**
     * Compares configuration value
     * @param string $category CAtegory
     * @param string $key Identifier
     * @param mixed $value Comparation value
     */
    public function Compare(string $category, string $key, $value) {

        try {

            $clist = $this->Configs->{$category};
            $record = $clist->{$key};

            if(is_bool($value))
                $value = b2int($value);

            return $record === $value;

        } catch (Exception $exc) {

            throw new EngineConfigException("Cannot get configuration to comparation");
        }
    }

    /**
     * Gets configuration by key in category
     * @param string $category Category
     * @param string $key Identifier
     * @param mixed|null $default Default value if not existing
     */
    public function GetConfig(string $category, string $key, $default = NULL) {

        try {

            $clist = $this->Configs->{$category};
            if($clist === NULL)
                throw new EngineConfigException('Engine config category not found');
            $record = $clist->{$key};

            return $record;

        } catch (Exception $exc) {

            return $default;
        }
    }
}

class LocalConfig {

    // .../src/local.cfg
    const DEFAULT_PATH = __DIR__."/local.cfg";

    private $Path;
    private $Data;

    /**
     * Constructor
     */
    public function __construct() {

        $this->Data = new Dictionary();
        $this->SetPath(self::DEFAULT_PATH);
    }


    /**
     * Sets path to file
     * @param string $path File path (when '' use configurator)
     */
    public function SetPath(string $path = '') {

        $engine = Engine::GetSingleton();
        if($path === '') {

            // try get main configurator - user custom path
            $path = $engine->GetConfigurator()->GetConfig('paths', 'local-config', '');
            $engine->Log('debug', "Trying using local config file path (%s)", $path);
        } 

        $this->Path = $path;
        $engine->Log('info', "Using local config file path (%s)", $this->Path);
    }

    /**
     * Parse config file
     * @throws \Exception
     */
    public function Parse() {

        $msgs = [];
        $engine = Engine::GetSingleton();
        try {

            $file = $this->Path;
            if ($handle = fopen($file, "r")) {

                $n = 0;
                while (($line = fgets($handle)) !== false) {

                    $n++;
                    $line = trim($line);
                    if ($line === '' || strpos($line, '=') === false || strpos($line, ';') === 0) {

                        $engine->Log('debug', 'Skipping line: "%s"', $line);
                        continue;
                    }
            
                    list($key, $value) = explode('=', $line, 2);
                    $this->Data->Add(trim($value), strtolower(trim($key)));
                }
            
                fclose($handle);

            } else {
                
                throw new \Exception("Cannot open file");
            }

        } catch (\Exception $exc) {

            throw new ConfigException("Parsing local config file ends up with error: %s", $exc->getMessage());

        } finally {

            $engine->Logm($msgs);
        }
    }

    /**
     * Gets Data value
     * @param string $key
     * @return string value
     * @throws ConfigException
     */
    public function GetDataValue(string $key) {

        try {

            return $this->Data->{$key};

        } catch(\Exception $exc) {

            throw new ConfigException("Cannot find key $key");
        }
    }
}
