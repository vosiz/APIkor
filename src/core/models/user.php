<?php

namespace Apikor\Core\Models;

class UserModel extends DbModel {

    protected $Email;    public function GetEmail()    { return $this->Email;    }
    protected $Password; public function GetPassword() { return $this->Password; }
    protected $Nick;     public function GetNick()     { return $this->Nick;     }
    protected $Valid;    public function IsValid()     { return $this->Valid;    }
    protected $RoleId;   public function GetRoleId()   { return $this->RoleId;   }

}
