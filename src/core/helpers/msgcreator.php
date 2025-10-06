<?php

namespace Apikor\Helpers;

use Vosiz\VaTools\Retval;
use Apikor\Response;

class MessageCreator {

    /** 
     * Plain text message
     * @param string $text Text to show
     * @return Response\Message (text)
     * @throws FakupException
     */
    public static function PlainText(string $text) {

        try {

            return Response\Message::CreatePlain($text);

        } catch (\Exception $exc) {

            fakup("PlainText message creation failure: ".$exc->getMessage());
        }
    }

    /**
     * Text array message 
     * @param array $a Array (values will be represented as text)
     * @return Response\Message (text array)
     * @throws FakupException
     */
    public static function TextArray(array $a = array()) {

        try {

            $a = tostr($a, true);
            return Response\Message::CreateArray($a);

        } catch (\Exception $exc) {

            fakup("TextArray message creation failure: ".$exc->getMessage());
        }
    }

    /** 
     * Retval 
     * @param string $type Retval type (enum name)
     * @param string $fmt Format with arguments
     * @return Response\Message (Retval)
     * @throws FakupException
    */
    public static function Retval(string $type, string $fmt, ...$args) {

        try {

            $retval = Retval::Create($type, $fmt, ...$args);
            return Response\Message::CreateRetval($retval);

        } catch (\Exception $exc) {

            fakup("Retval message creation failure: ".$exc->getMessage());
        }
    }

    /**
     * Data/Models
     * @param Model[array]|Model $data Array of models or single (makes it to array)
     * @return Response\Message (Data)
     * @throws FakupException
     */
    public static function Models($data = []) {

        try {

            // accepts single Model aswell
            if(!is_array($data)) {

                $data = \asarray($data);
            }

            if(empty($data)) {

                // TODO: what to return? retval? text?
                throw new FakupException("Model array is empty");

            } else {

                return Response\Message::CreateData($data);
            }

        } catch (\Exception $exc) {

            throw new FakupException("Data message creation failure: ".$exc->getMessage());
        }
        
    }
    
}