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

    /** 
     * Constructor
     * @param Message $msg Message
    */
    public function __construct(Message $msg) {

        $this->Type = $msg->GetType();
        $this->Timestamp = now();
    }
}

class ResponseBody {

    private $Payload;
    private $Debug;

    /** 
     * Constructor
     * @param Message $msg Message
     */
    public function __construct(Message $msg) {
        
        $this->Debug = new ResponseDebug();
        $this->Payload = $msg;
    }
}

class Response {

    private $Header;
    private $Body;

    /** 
     * Instantiate
     * @param Message $msg Message
    */
    public static function Create(Message $msg) {   

        return new self($msg);
    }

    /** 
     * Constructor
     * @param Message $msg Message
     */
    protected function __construct(Message $msg) {

        $this->Header   = new ResponseHeader($msg);
        $this->Body     = new ResponseBody($msg);
    }

}