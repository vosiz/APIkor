<?php 

namespace Apikor\Response;

use Vosiz\Utils\Collections\Collection;
use Vosiz\Enums\Enum;
use Vosiz\VaTools\Retval;

class MessageTypeEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'unknown'   => 0x00,    // undefined
            'plain'     => 0x10,    // plain text
            'array'     => 0x11,    // array of text
            'binary'    => 0x12,    // array of binary data [32bit len][...data]
            'retval'    => 0x70,    // Retval class
            'custom'    => 0xC0,    // custom data structure - own class
            'data'      => 0xD0,    // array of models
        ];
        self::AddValues($vals);
    } 
}

class Message {

    private $Type;  public function GetType()   { return $this->Type;   }
    private $Data;  public function GetData()   { return $this->Data;   }

    /** TODO: */
    public static function CreateRetval(Retval $retval) { 

        return self::Create(MessageTypeEnum::GetEnum('retval'), $retval);    
    }

    /** TODO: */
    public static function CreatePlain($plain) {

        return self::Create(MessageTypeEnum::GetEnum('plain'), $plain);
    }


    /** TODO: */
    public static function Create(MessageTypeEnum $type, $data = NULL) {

        $msg = new self($type, $data);
        return $msg;
    }

    /** TODO: */
    protected function __construct(MessageTypeEnum $type, $data = NULL) {

        $this->Type = $type;
        $this->Data = $data;

        if($data === NULL) {

            switch($type) {

                default:
                    throw new \UndefinedStateException($type->__toString());
            }
        }
        
    }

    
}