<?php

namespace Apikor;

class DbModel extends \Apikor\MapModel {

    protected $Id;          public function GetId()         { return $this->Id;         }
    protected $Active;      public function IsActive()      { return $this->Active;     }
    protected $Timestamp;   public function GetTimestamp()  { return $this->Timestamp;  }
    protected $Update;      public function GetUpdate()     { return $this->Update;     }
    protected $Apiv;        public function GetApiVersion() { return $this->ApiVersion; }
    protected $Manual;      public function IsManual()      { return $this->Manual;     }

    // Aliases
    protected function Created()    { return $this->GetTimestamp();     }
    protected function Updated()    { return $this->GetUpdate();        }
    protected function Apiv()       { return $this->GetApiVersion();    }

    // TODO: magic methods
}
