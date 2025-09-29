<?php

namespace Apikor;

class EngineDataContainer {

    const SECTION_KEY_DB        = 'db';
    const SECTION_KEY_SERVICE   = 'service';

    private $Providers = array();


    /**
     * Constructor
     * @param array $inject_data Initial setup data
     * @throws \Exception
     */
    public function __construct(array $inject_data) {

        try {

            // basic providers
            //$this->AddProvider(self::SECTION_KEY_DB, new DbProvider());
            $this->AddTrustedProvider(self::SECTION_KEY_DB, new DbProvider());
            $this->AddTrustedProvider(self::SECTION_KEY_SERVICE, new ServiceProvider($inject_data[self::SECTION_KEY_SERVICE])); // TODO

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