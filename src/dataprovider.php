<?php

namespace Apikor;

use \Apikor\Tools;

class EngineDataProvider {

    private $Container;
    private $Configurator;
    

    /** 
     * Constructor
     * @param \Apikor\EngineDataContainer $container Data container
    */
    public function __construct($container) {

        try {

            $this->Container = $container;

        } catch(\Exception $exc) {

            throw $exc;
        }
        
    }

    /** 
     * Gets data from container
     * @param EngineContainerSectionEnum $section Section key
     * @param string $key In section key
     * @throws \Exception
     */
    public function GetData(EngineContainerSectionEnum $section, string $key = null) {

        try {

            if($this->IsAllowed($section)) {

                return $this->Provide($section, $key);
            }

        } catch (\Exception $exc) {

            throw $exc;
        }

    }

    /** 
     * Sets data to container
     * @param EngineContainerSectionEnum $section Section key
     * @param string $key In section key
     * @param mixed $value Value
     * @throws \Exception
     */
    public function SetData(EngineContainerSectionEnum $section, string $key, $value) {

        try {

            if($this->IsAllowed($section)) {

                return $this->Inject($section, $key, $value);
            }

        } catch (\Exception $exc) {

            throw $exc;
        }

    }


    /**
     * Loads basic entities instnaces
     * @throws \Apikor\ContainerException
     */
    public function LoadEntities() {

        try {

            $this->Configurator = Engine::GetSingleton()->GetConfigurator();

            foreach($this->Container->GetEntities() as $ent) {
                
                $provider = $this->GetProvider($ent->GetType());
                $ent->Include($provider);
            }

        } catch(\Exception $exc) {

            throw new ContainerException("Data provision failed: ".$exc->getMessage());
        }

    }


    /** 
     * Check if provision is allowed
     * @param EngineContainerSectionEnum $name Section name
     * @return bool true if allowed
     * @throws \UnimplementedStateException
    */
    private function IsAllowed(EngineContainerSectionEnum $section) {

        $internals = EngineContainerSectionEnum::GetAll();
        if($internals->HasValue($section))
            return true;

        // TODO: externals

        throw new \UnimplementedStateException($name);
    }

    /**
     * Gets data provider by key
     * @param EngineContainerSectionEnum $id Data provider registration key
     * @return \Apikor\DataProvider
     * @throws \Exception
     */
    private function GetProvider(EngineContainerSectionEnum $id) {

        try {

            return $this->Container->GetProviderByKey($id);

        } catch (\Exception $exc) {

            throw new \Exception("Cannot get provider '$id', ".$exc->getMessage());
        }
    }

    /** 
     * Provides data
     * @param EngineContainerSectionEnum $section Data provider key
     * @param string $key Data part key
     * @return mixed
     * @throws BadMethodCallException
     */
    private function Provide(EngineContainerSectionEnum $section, string $key = NULL) {

        try {

            $provider = $this->GetProvider($section);

            return $key === NULL ? 
                $provider->List() : $provider->ByKey($key, true);

        } catch(\Exception $exc) {

            throw new \BadMethodCallException("No method found for section: $section; ".$exc->getMessage());
        }
    }

    /** 
     * Adds data to data provider
     * @param EngineContainerSectionEnum $section Section id
     * @param string $key Key
     * @param mixed $value Value
    */
    private function Inject(EngineContainerSectionEnum $section, string $key, $value) {

        $provider = $this->GetProvider($section);
        $provider->Inject($key, $value);
    }

}