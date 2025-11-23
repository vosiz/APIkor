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

    private $Name;      public function GetName()       { return $this->Name;       }
    private $Filepath;  public function GetFilepath()   { return $this->Filepath;   }
    private $Type;      public function GetType()       { return $this->Type;       } 
    private $Engine;

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

    /**
     * Creates model reference
     * @param string $path Relative path to directory (without filename)
     * @param string $model Model name
     * @param string $namespace Namespace
     */
    public static function CreateModel(string $path, string $model, string $namespace, ...$args) {

        $filepath = sprintf(__DIR__.'/../../models/%s/%s.php', $path, $model);
        return self::Create('model', $filepath, $model, $namespace, ...$args);
    }

    /**
     * Creates mapper reference
     * @param string $path Relative path to directory (without filename)
     * @param string $mapper Mapper name
     * @param string $namespace Namespace
     */
    public static function CreateMapper(string $path, string $mapper, string $namespace, ...$args) {

        $filepath = sprintf(__DIR__.'/../../mappers/%s/%s.php', $path, $mapper);
        return self::Create('mapper', $filepath, $mapper, $namespace, ...$args);
    }

    /**
     * Creates service reference
     * @param string $path Relative path to directory (without filename)
     * @param string $service Name of service (also filename - snake_case)
     * @param string $namespace Namespace
     */
    public static function CreateService(string $path, string $service, string $namespace = 'Apikor', ...$args) {

        $filepath = sprintf(__DIR__.'/../../services/%s/%s.php', $path, $service);
        return self::Create('service', $filepath, $service, $namespace, ...$args);
    }

    /**
     * Creates apikor entity
     * @param string $type Type of entity
     * @param string $path Full path to entity
     * @param string $key Key using it (snake_case)
     * @param string $namespace Namespace (without '\')
     * @throws \Exception
     */
    public static function Create(string $type, string $filepath, string $key, string $namespace = 'Apikor', ...$args) {

        try {

            Commons::Require($filepath);
            
            // class: <\Namespace>\<Type>s\<KeyName><Type>
            $ucf_type = ucfirst($type); // first char in upper
            $cls_type = \camel_str($key); // camel cased 
            $cls = sprintf("\\%s\\%ss\\%s%s", $namespace, $ucf_type, $cls_type, $ucf_type);

            $inst = new $cls(...$args);
            $inst->Filepath = $filepath;
            $inst->Type = EngineContainerSectionEnum::GetEnum($type);
            $inst->Name = $key;
            $inst->Engine = $inst->GetEngine();

            return $inst;

        } catch (\Exception $exc) {

            throw $exc;
        }

    }


    /**
     * Include and injects entity to data provider
     * @param \Apikor\DataProvider $provider To what provider to inject
     * @throws \Exception
     */
    public function Include(DataProvider $provider) {

        try {

            Commons::Require($this->Filepath);
            $provider->Inject($this->Name, $this);            

        } catch(\Exception $exc) {

            throw new \Exception("Include failed, ".$exc->getMessage());
        }
        
    }


    /**
     * Gets engine
     * @return \Apikor/Engine
     */
    protected function GetEngine() {

        return Engine::GetSingleton();
    }

}