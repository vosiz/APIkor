<?php

namespace Apikor\Response;

class Response {

    private $Header;    public function GetHeader()  { return $this->Header;  }
    private $Payload;   public function GetPayload() { return $this->Payload; }

    /**
     * Constructor
     */
    private function __construct(Header $header, Payload $payload) {

        $this->Header  = $header;
        $this->Payload = $payload;
    }

    /**
     * Creates response
     * @param Header $header
     * @param Payload $payload
     * @return Response
     */
    public static function Create(Header $header, Payload $payload) {

        return new self($header, $payload);
    }

}
