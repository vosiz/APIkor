<?php

namespace Apikor\Core\Models;

use Apikor\Core\Mappers\Mapper;

abstract class Model {

}

abstract class DataModel extends Model {

    protected static $MapperType = null;
    protected $Mapper;

}
