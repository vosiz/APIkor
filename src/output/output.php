<?php

namespace Apikor\Output;


abstract class Formatter implements IFormat{

    public static function Translate(string $format) {

        switch($format) {

            case 'var':
                return new VarFormat();
                break;

            default:
                throw new \UnimplementedStateException($format);
        }
    }
}