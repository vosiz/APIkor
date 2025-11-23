<?php

namespace Apikor;

abstract class Mapper extends Entity {

    /**
     * Maps class to another class
     * @param object $cls From object
     * @param object $to To object
     * @return object reflected class
     * @throws \ReflectionException
     */
    protected function Map(object $cls, object $to) {

        try {

            $refl = new \ReflectionClass($to);
            foreach (get_object_vars($cls) as $key => $value) {
                
                $key = camel($key);
                if ($refl->hasProperty($key)) {
                    
                    $prop = $refl->getProperty($key);
                    $prop->setAccessible(true);
                    $prop->setValue($to, $value);
                }
            }

            return $to;

        } catch(\Exception $exc) {

            throw new \ReflectionException("Cannot convert '$cls->__toString()' to '$to->__toString()'");
        }
        
    }
}

