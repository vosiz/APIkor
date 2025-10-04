<?php

namespace Apikor;

abstract class Mapper extends Entity {

    protected function Map(object $cls, object $to) {

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
    }
}

