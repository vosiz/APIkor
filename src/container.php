<?php

namespace Apikor;

use Vosiz\Enums\Enum;

class EngineContainerSectionEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'uncategorized' => 0x00,
            'db'            => 0x10,
            'module'        => 0x20,
        ];

        foreach(EntityTypeEnum::GetAll()->AsArray() as $k => $enum) {
            $vals[$enum->GetName()] = $enum->GetValue();
        }
        self::AddValues($vals);
    } 
}

class EngineDataContainer {

    private $Providers = array();
    private $Entities;  public function GetEntities() { return $this->Entities; }

    /**
     * Constructor
     * @throws \Exception
     */
    public function __construct() {

        try {

            // basic providers
            $this->AddTrustedProvider(EngineContainerSectionEnum::GetEnum('db'), new DbProvider());
            $this->AddTrustedProvider(EngineContainerSectionEnum::GetEnum('controller'), new ControllerProvider());
            $this->AddTrustedProvider(EngineContainerSectionEnum::GetEnum('service'), new ServiceProvider());
            $this->AddTrustedProvider(EngineContainerSectionEnum::GetEnum('model'), new ModelProvider());
            $this->AddTrustedProvider(EngineContainerSectionEnum::GetEnum('mapper'), new MapperProvider());

            $this->Entities = INC_Entities();

        } catch (\Exception $exc) {

            throw $exc;
        }
        
    }

    /** 
     * Returns data provider by key
     * @param string $key Id of provider
     * @return \Apikor\DataProvider
     * @throws \ContainerException
     */
    public function GetProviderByKey(string $key) {

        try {

            return $this->Providers[$key];

        } catch(\Exception $exc) {

            throw new ContainerException("Provider $key is not found");
        }
    }
    
    /** Adds provider to pool 
     * @param string $key Identification of provider
     * @param DataProvider $provider DataProvider instance
    */
    public function AddProvider(string $key, DataProvider $provider) {

        throw new NotImplementedYet("Custom data providers are not supported yet");
    }


    /** 
     * Adds data provider to pool - basic, controlled, trusted
     * @param string $key Identification of DataProvider
     * @param DataProvider $provider DataProvider instance
     */
    private function AddTrustedProvider(string $key, DataProvider $provider) {

        $this->Providers[$key] = $provider;
    }

    

}