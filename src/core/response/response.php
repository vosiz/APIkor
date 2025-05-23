<?php

namespace Apikor\Response;

class ResponseDebug {

    
}

class ResponseHeader {

    private $Version    = 1;
    private $Type;
    private $Timestamp;
    private $Code       = 0x00;
    private $Request;
    private $Client;

    /** TODO: */
    public function __construct(Message $msg) {

        $this->Type = $msg->GetType();
        $this->Timestamp = now();
    }
}

class ResponseBody {

    private $Payload;
    private $Debug;

    /** TODO: */
    public function __construct(Message $msg) {
        
        $this->Debug = new ResponseDebug();
        $this->Payload = $msg;
    }
}

class Response {

    private $Header;
    private $Body;

    /** TODO: */
    public static function Create(Message $msg) {   

        return new self($msg);
    }

    /** TODO: */
    protected function __construct(Message $msg) {

        $this->Header   = new ResponseHeader($msg);
        $this->Body     = new ResponseBody($msg);
    }

}