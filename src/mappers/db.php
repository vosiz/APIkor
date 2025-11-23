<?php

namespace Apikor\Mappers;

class DbTableMapper extends \Apikor\Mapper { 

    /**
     * Maps DB rows to array of Models
     * @param \Apikor\Model $model Desired model (nullable)
     * @param array $rows Probably stdClass[] from db
     * @return \Apikor\Model[] mapped instances
     */
    public function ToModel(?\Apikor\Model $model, $rows) {

        if(!is_array($rows)) {

            $rows = \asarray($rows);
        }

        if($model === NULL)
            return $rows; // do not convert

        $refl = [];
        foreach($rows as $row) {

            $refl[] = $this->Map($row, $model);
        }

        return $refl;
    }
}

// Alias class
class DbMapper extends DbTableMapper {

}
