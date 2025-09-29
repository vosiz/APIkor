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

    /** 
     * Creates Message - retval
     * @param Retval $retval Retval
     * @return Message
    */
    public static function CreateRetval(Retval $retval) { 

        return self::Create(MessageTypeEnum::GetEnum('retval'), $retval);    
    }

    /**
     * Creates Message - plain
     * @param string $plain Plain text
     * @return Message
     */
    public static function CreatePlain(string $plain) {

        return self::Create(MessageTypeEnum::GetEnum('plain'), $plain);
    }

    /** 
     * Creates Message - array
     * @param array $arr Text array
     * @return Message
    */
    public static function CreateArray(array $arr = array()) {

        return self::Create(MessageTypeEnum::GetEnum('array'), $arr);
    }

    /** 
     * Creates Message
     * @param MessageTypeEnum $type Message type
     * @param mixed $data Data
     * @return Message
    */
    public static function Create(MessageTypeEnum $type, $data = NULL) {

        $msg = new self($type, $data);
        return $msg;
    }


    /** 
     * Consturctor
     * @param MessageTypeEnum $type Message type
     * @param mixed $data Data
    */
    protected function __construct(MessageTypeEnum $type, $data = NULL) {

        $this->Type = $type;
        $this->Data = $data;

        // TODO: ?
        if($data === NULL) {

            switch($type) {

                default:
                    throw new \UndefinedStateException($type->__toString());
            }
        }
        
    }

    
}