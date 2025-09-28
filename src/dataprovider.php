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

        switch($section) {

            // allowed
            case \Apikor\EngineDataContainer::SECTION_KEY_DB:
                return $this->Provide(\Apikor\EngineDataContainer::SECTION_KEY_DB, $key);

            // case 'model':
            //     break;

            // case 'mapper':
            //     break;

            // case 'service':
            //     break;

            default:
                throw new \UnimplementedStateException($section);
        }
    }

    /** TODO: */
    public function SetData(string $section, string $key, $value) {

        switch($section) {

            // allowed
            case \Apikor\EngineDataContainer::SECTION_KEY_DB:
                return $this->Inject($section, $key, $value);

            default:
                throw new \UnimplementedStateException($section);
        }
    }


    /** TODO: */
    private function GetProvider($id) {

        try {

            return $this->Container->GetProviderByKey($id);

        } catch (\Exception $exc) {

            throw new \Exception("Cannot get provider '$id', ".$exc->getMessage());
        }
    }

    /** TODO: */
    private function Provide($section, $key = NULL) {

        try {

            $provider = $this->GetProvider($section);

            return $key === NULL ? 
                $provider->List() : $provider->ByKey($key);

        } catch(\Exception $exc) {

            throw new BadMethodCallException("No method found for section: $section; ".$exc->getMessage());
        }
    }

    /** TODO: */
    private function Inject($section, $key, $value) {

        $provider = $this->GetProvider($section);
        $provider->Inject($key, $value);
    }

}