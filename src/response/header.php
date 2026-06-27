<?php

namespace Apikor\Response;

use Vosiz\Enums\Enum;

class PayloadTypeEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'plain'     => 0x00,    // plain text
            'array'     => 0x11,    // associative array of primitives
            'binary'    => 0x12,    // binary data [32bit len][...data]
            'retval'    => 0x70,    // Retval class
            'custom'    => 0xC0,    // custom data structure - own class
            'debug'     => 0xD0,    // debug info
            'model'     => 0xE0,    // single Model
            'models'    => 0xE1,    // collection of Models
        ];
        self::AddValues($vals);
    }
}

class Header {

    const VERSION = 1; // initial

    private $Version;   public function GetVersion()   { return $this->Version;   }
    private $Type;      public function GetType()      { return $this->Type;      }
    private $Timestamp; public function GetTimestamp() { return $this->Timestamp; }
    private $Code;      public function GetCode()      { return $this->Code;      }
    private $Request;   public function GetRequest()   { return $this->Request;   }
    private $Client;    public function GetClient()    { return $this->Client;    }
    private $Uid;       public function GetUid()       { return $this->Uid;       }

    /**
     * Constructor
     * @param PayloadTypeEnum $type Payload type
     * @param int $code HTTP result code
     * @param \Apikor\Tools\UrlParser|null $parser URL parser instance
     * @param int $uid Authenticated user ID (0 = unauthenticated)
     */
    public function __construct(PayloadTypeEnum $type, int $code, ?\Apikor\Tools\UrlParser $parser = null, int $uid = 0) {

        $this->Version   = self::VERSION;
        $this->Type      = $type;
        $this->Timestamp = \now('Y-m-d H:i:s');
        $this->Code      = $code;
        $this->Uid       = $uid;

        if($parser !== null) {

            $this->Client  = $parser->GetClient();
            $this->Request = [
                'method' => $parser->GetMethod(),
                'url'    => $parser->GetUrl(),
                'parts'  => $parser->GetParts()
            ];

        } else {

            $this->Client  = '';
            $this->Request = ['method' => '', 'url' => '', 'parts' => []];
        }
    }

}
