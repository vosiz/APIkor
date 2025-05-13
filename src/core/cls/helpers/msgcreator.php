<?php

namespace Apikor\Helpers;

use Vosiz\VaTools\Retval;
use Apikor\Response;

class MessageCreator {

    /** TODO: */
    public static function PlainText(string $text) {

        try {

            return Response\Message::CreatePlain($text);

        } catch (Exception $exc) {

            //Fakup("PlainText message creation failure: ".$exc->getMessage());
        }
    }

    /** TODO: */
    public static function Retval(string $type, string $fmt, ...$args) {

        try {

            $retval = Retval::Create($type, $fmt, ...$args);
            return Response\Message::CreateRetval($retval);

        } catch (Exception $exc) {

            //Fakup("Retval message creation failure: ".$exc->getMessage());
        }
    }

    
    
}