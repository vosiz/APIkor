<?php

namespace Apikor;

abstract class DataProvider  {

    private $Data = array();

    abstract public function Init(array $data);
    
    /** TODO: */
    public function List() {

        return $this->Data;
    }

    /** TODO: */
    public function ByKey(string $key, bool $strict = false) {

        try {

            if($strict)
                if(!isset($this->Data[$key]))
                    throw new \Exception("'$key' not defined in Dataprovider.Data");
            else
                return \getifset($this->Data, $key);

        } catch(\Exception $exc) {

            throw $exc;
        }
    }

    /** TODO: */
    public function Inject(string $key, $value) {

        $this->Data[$key] = $value;
    }

}