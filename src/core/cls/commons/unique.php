<?php

namespace Apikor;

class UniqueIdentificator {

    private $Identificator; public function GetId()     { return $this->Identificator; }
    private $Type;          public function GetType()   { return $this->Type; }
    private $Name;          public function GetName()   { return $this->Name; }


    /** TODO: */
    public function __construct($id, $type = 'string', $name = 'email') {

        $this->Identificator = $id;
        $this->Type = $type;
        $this->Name = $name;
    }
}


interface IIdentification {

    public function _SetId(UniqueIdentificator $uid);
}