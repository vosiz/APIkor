<?php

namespace Apikor\Core\Models;

abstract class DbRawModel extends DataModel {

    protected $Id;  public function GetId() { return $this->Id; }

    /**
     * Creates model instance(s) from DB row(s)
     * @param \stdClass|array $rows Single row or array of rows
     * @return static|static[]
     */
    public static function Create($rows) {

        if($rows instanceof \stdClass)
            return self::CreateSingle($rows);

        $result = [];
        foreach($rows as $row)
            $result[] = self::CreateSingle($row);
        return $result;
    }

    private static function CreateSingle(\stdClass $row) {

        $instance = new static();
        $instance->Mapper = \Apikor\Engine::GetInstance()->GetContainer()->Get(static::$MapperType);
        $instance->Mapper->Map($row, $instance);
        return $instance;
    }

}

abstract class DbModel extends DbRawModel {

    protected $Active;      public function IsActive()      { return $this->Active;     }
    protected $Timestamp;   public function GetTimestamp()  { return $this->Timestamp;  }
    protected $Update;      public function GetUpdate()     { return $this->Update;     }
    protected $Apiv;        public function GetApiVersion() { return $this->Apiv;       }
    protected $Manual;      public function IsManual()      { return $this->Manual;     }

    protected function Created()    { return $this->GetTimestamp();  }
    protected function Updated()    { return $this->GetUpdate();     }
    protected function Version()    { return $this->GetApiVersion(); }

}
