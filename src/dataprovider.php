<?php

namespace Apikor;

class EngineDataProvider {

    private $Container;

    /** 
     * Constructor
     * @param \Apikor\EngineDataContainer $container Data container
    */
    public function __construct($container) {

        $this->Container = $container;
    }

    /** 
     * Gets data from container
     * @param string $section Section key
     * @param string $key In section key
     * @throws \UnimplementedStateException
     */
    public function GetData(string $section, string $key = null) {

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
     * @param string $section Section key
     * @param string $key In section key
     * @param mixed $value Value
     * @throws \Exception
     */
    public function SetData(string $section, string $key, $value) {

        try {

            if($this->IsAllowed($section)) {

                return $this->Inject($section, $key, $value);
            }

        } catch (\Exception $exc) {

            throw $exc;
        }

    }

    /** 
     * Check if provision is allowed
     * @param string $name Section name
     * @return bool true if allowed
     * @throws \UnimplementedStateException
    */
    private function IsAllowed(string $name) {

        switch($name) {

            case \Apikor\EngineDataContainer::SECTION_KEY_DB:
            case \Apikor\EngineDataContainer::SECTION_KEY_SERVICE:
                return true;

            default:
                throw new \UnimplementedStateException($name);
        }
    }

    /**
     * Gets data provider by key
     * @param string $id Data provider registration key
     * @return \Apikor\DataProvider
     * @throws \Exception
     */
    private function GetProvider(string $id) {

        try {

            return $this->Container->GetProviderByKey($id);

        } catch (\Exception $exc) {

            throw new \Exception("Cannot get provider '$id', ".$exc->getMessage());
        }
    }

    /** 
     * Provides data
     * @param $section Data provider key
     * @param $key Data part key
     * @return mixed
     * @throws BadMethodCallException
     */
    private function Provide(string $section, $key = NULL) {

        try {

            $provider = $this->GetProvider($section);

            return $key === NULL ? 
                $provider->List() : $provider->ByKey($key);

        } catch(\Exception $exc) {

            throw new BadMethodCallException("No method found for section: $section; ".$exc->getMessage());
        }
    }

    /** 
     * Adds data to data provider
     * @param string $section Section id
     * @param string $key Key
     * @param mixed $value Value
    */
    private function Inject(string $section, string $key, $value) {

        $provider = $this->GetProvider($section);
        $provider->Inject($key, $value);
    }

}