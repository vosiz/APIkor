<?php

namespace Apikor;

abstract class DataProvider  {

    private $Data = array();

    abstract public function Init(array $data);
    
    /** 
     * List of data
     * @return mixed|array
    */
    public function List() {

        return $this->Data;
    }

    /** 
     * Data by key
     * @param string $key Key
     * @param bool If true and not defined throws Excpetion
     * @return mixed data
     * @throws \Exception
     */
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

    /** 
     * Injects data
     * @param string $key Id
     * @param mixed $value Value
     */
    public function Inject(string $key, $value) {

        $this->Data[$key] = $value;
    }

}