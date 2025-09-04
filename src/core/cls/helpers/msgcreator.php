<?php

namespace Apikor\Helpers;

use Vosiz\VaTools\Retval;
use Apikor\Response;

class MessageCreator {

    /** TODO: */
    public static function PlainText(string $text) {

        try {

            return Response\Message::CreatePlain($text);

        } catch (\Exception $exc) {

            throw $exc;
            //Fakup("PlainText message creation failure: ".$exc->getMessage());
        }
    }

    /** TODO: */
    public static function TextArray(array $a = array()) {

        try {

            return Response\Message::CreateArray($a);

        } catch (\Exception $exc) {

            throw $exc;
        }
    }

    /** TODO: */
    public static function Retval(string $type, string $fmt, ...$args) {

        try {

            $retval = Retval::Create($type, $fmt, ...$args);
            return Response\Message::CreateRetval($retval);

        } catch (\Exception $exc) {

            throw $exc;
            //Fakup("Retval message creation failure: ".$exc->getMessage());
        }
    }

    
    
}