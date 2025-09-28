<?php

namespace Apikor;

class EngineDataContainer {

    const SECTION_KEY_DB = 'db';

    private $Providers = array();


    /** TODO: */
    public function __construct($inject_data = array()) {

        // basic providers
        //$this->AddProvider(self::SECTION_KEY_DB, new DbProvider());
        $this->AddTrustedProvider(self::SECTION_KEY_DB, new DbProvider());
    }


    /** TODO: */
    public function GetProviderByKey(string $key) {

        try {

            return $this->Providers[$key];

        } catch(\Exception $exc) {

            throw new ContainerException("Provider $key is not found");
        }
    }
    
    /** TODO: */
    public function AddProvider(string $key, DataProvider $provider) {

        throw new NotImplementedYet("Custom data providers are not supported yet");
    }

    /** TODO: */
    private function AddTrustedProvider(string $key, DataProvider $provider) {

        $this->Providers[$key] = $provider;
    }

}