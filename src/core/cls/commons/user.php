<?php

namespace Apikor;

// Email-based user (unique is email)
class User implements IIdentification {

    private $Identificator;
    private $Username;
    private $Nickname;
    private $FirstName;
    private $LastName;
    private $Email;
    private $Phone;

    /** TODO: */
    public function __construct(string $username, string $email) {

        $this->Identificator = $this->_SetId(new UniqueIdentificator($email));
        $this->Email = $email;
        $this->Username = $username;
        $this->Nickname = $username;
    }

    /** TODO: */
    public function _SetId(UniqueIdentificator $id) {

        $this->Identificator = $id;
    }
    
}