<?php

namespace Apikor\Core\Mappers;

abstract class DbMapper extends Mapper {

    /**
     * Maps DB row to model instance via reflection
     * @param \stdClass $row DB row
     * @param object $instance Target model instance
     * @return object
     * @throws \ReflectionException
     */
    public function Map(\stdClass $row, object $instance) {

        $refl = new \ReflectionClass($instance);
        foreach(get_object_vars($row) as $key => $value) {

            $prop = camel($key);
            if($refl->hasProperty($prop)) {

                $p = $refl->getProperty($prop);
                $p->setAccessible(true);
                $p->setValue($instance, $value);
            }
        }

        return $instance;
    }

}
