<?php

namespace Apikor;

require_once(__DIR__.'/base.php');

class DbTableMapper extends BaseMapper {

    protected $TableName;

    protected $Id;          public function GetId()         { return $this->Id;         }
    protected $Active;      public function IsActive()      { return $this->Active;     }
    protected $Timestamp;   public function GetTimestamp()  { return $this->Timestamp;  }
    protected $Update;      public function GetUpdate()     { return $this->Update;     }
    protected $ApiVersion;  public function GetApiVersion() { return $this->ApiVersion; }
    protected $Manual;      public function IsManual()      { return $this->Manual;     }

    // Aliases
    protected function Created()    { return $this->GetTimestamp();     }
    protected function Updated()    { return $this->GetUpdate();        }
    protected function Apiv()       { return $this->GetApiVersion();    }

}