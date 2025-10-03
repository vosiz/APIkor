<?php

namespace Apikor;

abstract class Service extends Entity {

    public function _Setup() {

        try {

            return $this;

        } catch(\Exception $exc) {

            throw new ContainerException("Unable to setup service");
        }
        
    }
}
