<?php

namespace Apikor;

require_once(__DIR__.'/core/diagnostics/enums.php');
require_once(__DIR__.'/core/diagnostics/message.php');

use Vosiz\Utils\Collections\Collection as Collection;
use Vosiz\Enums\Enum as Enum;

class EngineDiagnostics {
    
    private static $Messages;
    private static $Engine;

    private static $DefaultLevel;

    /**
     * Constructor
     * @param Engine $engine Engine singleton
     */
    public function __construct(Engine $engine) {

        self::$Engine = $engine;
        self::$Messages = new Collection();

        self::$DefaultLevel = EngineDiagnosticsLevelEnum::GetEnum('error');
    }   

    public static function Debug($fmt, ...$args) {

        self::CreateMessage(EngineDiagnosticsLevelEnum::GetEnum('debug'), $fmt, ...$args);
    }

    public static function Info($fmt, ...$args) {

        self::CreateMessage(EngineDiagnosticsLevelEnum::GetEnum('info'), $fmt, ...$args);
    }

    public static function Warning($fmt, ...$args) {

        self::CreateMessage(EngineDiagnosticsLevelEnum::GetEnum('warning'), $fmt, ...$args);
    }

    public static function Error($fmt, ...$args) {

        self::CreateMessage(EngineDiagnosticsLevelEnum::GetEnum('error'), $fmt, ...$args);
    }

    public static function Fatal($fmt, ...$args) {

        self::CreateMessage(EngineDiagnosticsLevelEnum::GetEnum('fatal'), $fmt, ...$args);
    }

    public static function Exc($fmt, ...$args) {

        self::CreateMessage(EngineDiagnosticsLevelEnum::GetEnum('exc'));
    }

    public static function CreateMessage(EngineDiagnosticsLevelEnum $type, $fmt, ...$args) {

        $message = new Message($type, $fmt, ...$args);
        self::$Messages->Add($message);
    }

    /**
     * 
     */
    public function PrintConfigs() {

        if(self::$Engine->GetConfigurator()->Compare('diagnostics', 'print_configs', false))
            return;

        $configs = self::$Engine->GetConfigurator()->GetConfigs();
        echo "Configurations</br>";
        foreach($configs->ToArray() as $category => $config)  {

            echo "+ ".$category."</br>";
            foreach($config->ToArray() as $k => $v) {

                echo sprintf("-- %s: %s</br>", $k, $this->ConfigValueToString($v));
            }
        }
    }

    /**
     * 
     */
    public function PrintMessages() {
    
        if(self::$Engine->GetConfigurator()->Compare('diagnostics', 'print_messages', false))
            return;

        $higher_than = self::$DefaultLevel; 
        $lvl = self::$Engine->GetConfigurator()->GetConfig('diagnostics', 'level');
        if($lvl)
            $higher_than = $lvl;

        $cnt = self::$Messages->Count();
        echo "Total messages: ".$cnt.'</br>';
        if($cnt > 0) {

            echo "Filtration-level: ".$higher_than->GetName().'+</br>';
            foreach(self::$Messages->ToArray() as $m) {
                
                if($m->GetLevel()->GetValue() >= $higher_than->GetValue())
                    echo $m->__toString().'</br>';
            }
        }
    }

    /**
     * 
     */
    private function ConfigValueToString($value) {

        if($value instanceof Enum) {

            return sprintf("enum (%s) - [\"%s\"] = 0x%02x (%d)", $value->GetType(), $value->GetKey(), $value->GetValue(), $value->GetValue());

        } else if ($value instanceof Flagword) {

            // TODO:

        } else {

            return tostr($value);
        }
    }
}