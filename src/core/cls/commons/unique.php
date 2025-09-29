<?php

namespace Apikor;

class UniqueIdentificator {

    private $Identificator; public function GetId()     { return $this->Identificator; }
    private $Type;          public function GetType()   { return $this->Type; }
    private $Name;          public function GetName()   { return $this->Name; }


    /** 
     * Constructor
     * @param mixed $id Unique identificator
     * @param string $type By type
     * @param string $name Name
    */
    public function __construct($id, string $type = 'string', string $name = 'email') {

        $this->Identificator = $id;
        $this->Type = $type;
        $this->Name = $name;
    }
}


interface IIdentification {

    public function _SetId(UniqueIdentificator $uid);
}