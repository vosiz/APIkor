<?php

namespace Apikor;

class Message {

    private $Timestamp; public function GetTimestamp() {    return $this->Timestamp;    }
    private $Message;   public function GetMessage() {      return $this->Message;      }
    private $LevelEnum; public function GetLevel() {        return $this->LevelEnum;    }

    /**
     * Constructor
     * @param Enum $level Message level
     * @param string $fmt Format
     * @param mixed ...$args VA
     */
    public function __construct(EngineDiagnosticsLevelEnum $level, string $fmt, ...$args) {

        $this->LevelEnum = $level;
        $this->Message = sprintf($fmt, ...$args);
        $this->Timestamp = now('Y-m-d H:i:s');
    }

    /**
     * ToString override
     */
    public function __toString() {

        return sprintf("%s | [%3s]: %s", $this->Timestamp, $this->LevelEnum->GetDisplay(), $this->Message);
    }
}