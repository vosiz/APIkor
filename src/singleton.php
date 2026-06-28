<?php

namespace Apikor;

use Vosiz\Utils\Collections\Dictionary;

abstract class Singleton
{
    private static ?Dictionary $Instances = null;

    public static function GetInstance()
    {
        $class = static::class;
        return self::$Instances->$class;
    }


    final protected function RegisterInstance()     {
        if (self::$Instances === null)
            self::$Instances = new Dictionary();

        $class = static::class;
        self::$Instances->Add($this, $class);
    }

}