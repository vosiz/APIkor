<?php

namespace Apikor;

use \Vosiz\Enums\Enum;

class EntityTypeEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'controller'    => 0x30,
            'service'       => 0x31,
            'mapper'        => 0x32,
            'model'         => 0x33,
        ];
        self::AddValues($vals);
    } 
}

// APIKOR entity
abstract class Entity {

    const APP_ENTITY_PREFIX = "apikor_app";

    private $Name;      public function GetName()       { return $this->Name;       }
    private $Filepath;  public function GetFilepath()   { return $this->Filepath;   }
    private $Type;      public function GetType()       { return $this->Type;       } 


    // TODO:
    public static function TableName(string $table_name) {

        return sprintf("%s_%s", self::APP_ENTITY_PREFIX, $table_name);
    }

    /**
     * Creates controller entity
     * @param string $module $Module name
     * @param string $key Controller name/file/class
     * @param string $namespace Namespace name (full)
     * @param mixed $args (optional) args to controller
     */
    public static function CreateController(string $module, string $key, string $namespace, ...$args) {

        try {

            $filepath = __DIR__.'/../../modules/'.$module.'/'.$key.'.php';
            Commons::Require($filepath);
            $cls = sprintf("%s\%sModule\%sController", $namespace, ucfirst($module), ucfirst($key));

            $inst = new $cls($args);
            $inst->Filepath = $filepath;
            $inst->Type = EngineContainerSectionEnum::GetEnum('controller');
            $inst->Name = $key;

            return $inst;

        } catch(\Exception $exc) {

            throw new \Exception("Cannot create entity controller ($key): ".$exc->getMessage());
        }
        
    }

    // TODO:
    public static function CreateService(string $service, string $namespace, ...$args) {

        $filepath = sprintf(__DIR__.'/../../services/'.$service.'.php');
        return self::Create('service', $filepath, $service, $namespace, ...$args);
    }

    // TODO
    public static function Create(string $type, string $filepath, string $key, string $namespace, ...$args) {

        Commons::Require($filepath);
        $cls_type = ucfirst($type);
        $cls = sprintf("%s\%ss\%s%s", $namespace, $cls_type, ucfirst($key), $cls_type);

        $inst = new $cls(...$args);
        $inst->Filepath = $filepath;
        $inst->Type = EngineContainerSectionEnum::GetEnum($type);
        $inst->Name = $key;

        return $inst;
    }

    // TODO:
    // public static function Create(EntityTypeEnum $type, string $key, string $namespace, string $cls_name = null, string $filename = null) {

    //     if($cls_name == null)
    //         $cls_name = $key;

    //     if($filename == null)
    //         $filename = $key;
        
    //     try {

    //        // new instance



    //        return null;

    //     } catch(\Exception $exc) {

    //         throw new \Exception("Entity.Create failed, ".$exc->getMessage());
    //     }
    // }


    // TODO:
    public function Include(DataProvider $provider) {

        try {

            Commons::Require($this->Filepath);
            $provider->Inject($this->Name, $this);            

        } catch(\Exception $exc) {

            throw new \Exception("Include failed, ".$exc->getMessage());
        }
        
    }


}