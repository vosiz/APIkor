<?php

namespace Apikor;

// Class which services and provides data
abstract class DataService extends Service {

    // public function __construct() {}

    public function _Setup() {

        try {

            parent::_Setup();
            return $this;

        } catch(\Exception $exc) {

            throw new ContainerException("Unable to setup dataservice");
        }
        
    }
}