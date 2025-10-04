<?php

namespace Apikor\Mappers;

class DbTableMapper extends \Apikor\Mapper { }

class DbMapper extends DbTableMapper { 

    public function ToModel(\Apikor\Model $model, $rows) {

        $return_single = false;

        if(!is_array($rows)) {

            $rows = \asarray($rows);
        }

        $refl = [];
        foreach($rows as $row) {

            $refl[] = $this->Map($row, $model);
        }

        return $refl;
    }


}