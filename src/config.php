<?php

namespace Apikor;

use Vosiz\Enums\Enum as Enum;
use Vosiz\Structure\Flagword as Fword;
use Vosiz\Utils\Collections\Collection as Collection;

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
            'diagnostics' => 0,
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
    public function __construct(string $key, $value, string $category) {

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
    public static function ToConfig(string $key, $value, string $category) {

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

    private $Configs;   public function GetConfigs() {  return $this->Configs; }

    /** 
     * Constructor
    */
    public function __construct() {

        $this->Configs = new Collection();
        foreach(EngineConfigEnum::GetAll()->ToArray() as $enum) {

            $this->Configs->Add(new Collection(), $enum->GetKey());
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
            $record = $clist->{$key};

            return $record;

        } catch (Exception $exc) {

            return $default;
        }
    }
}
