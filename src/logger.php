<?php

namespace Apikor;

use Vosiz\Enums\Enum;
use Vosiz\Utils\Collections\Collection;
use Vosiz\Utils\TimeFormat;

class LoggerException extends \Exceptionf {

    /**
     * Constructor
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}


class LogLevelEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        /**
         * [SSxx IxDF]b
         * ==========================================================
         * S - severity: 3 = exc, 2 = error, 1 = warn, 0 = info
         * I - info:     1 = is info
         * D - debug:    1 = is debug
         * F - flag:     1 = important
         */
        $vals = [
            'exc'   => [0xC0, 'EXC'], // 1100 0000
            'err'   => [0x80, 'ERR'], // 1000 0000
            'warn'  => [0x40, 'WRN'], // 0100 0000
            'info'  => [0x0B, 'INF'], // 0000 1011
            'note'  => [0x08, 'NOT'], // 0000 1000
            'dbg'   => [0x03, 'DBG'], // 0000 0011
            'trc'   => [0x01, 'TRC'], // 0000 0001
        ];
        self::AddValues($vals);
    } 
}


class LogEntry {

    private LogLevelEnum $Level;
    private $Message;
    private $Timestamp;

    /**
     * Constructor
     */
    public function __construct(LogLevelEnum $level, string $fmt, ...$args) { 

        $this->Level = $level;
        $this->Message = sprintf($fmt, ...$args);
        $this->Timestamp = TimeFormat::Create();
    }

    /**
     * ToString
     */
    public function ToString() {

        return sprintf("%s | [%s]: %s\r\n", 
            $this->Timestamp,
            $this->Level->GetDisplay($this->Level),
            $this->Message
        );
    }
}


final class Logger extends Singleton {

    private $Level;
    private $FileLogAllowed = false;
    private $DbLogAllowed = false;

    private $BaseFilePath;
    private $Filename;

    private Collection $Messages;


    /**
     * Constructor
     */
    public function __construct() {

        $this->RegisterInstance();

        $this->Messages = new Collection();
        $this->Level = LogLevelEnum::GetEnum('info');
        $this->SetupFileLog(__DIR__.'/../logs');
    }

    // Aliases
    public function Exc     (string $fmt, ...$args) { try{ $this->Log(LogLevelEnum::GetEnum('exc'),  $fmt, ...$args); } catch(\Exception $exc) { throw $exc; } }
    public function Error   (string $fmt, ...$args) { try{ $this->Log(LogLevelEnum::GetEnum('err'),  $fmt, ...$args); } catch(\Exception $exc) { throw $exc; } }
    public function Warn    (string $fmt, ...$args) { try{ $this->Log(LogLevelEnum::GetEnum('warn'), $fmt, ...$args); } catch(\Exception $exc) { throw $exc; } }
    public function Info    (string $fmt, ...$args) { try{ $this->Log(LogLevelEnum::GetEnum('info'), $fmt, ...$args); } catch(\Exception $exc) { throw $exc; } }
    public function Note    (string $fmt, ...$args) { try{ $this->Log(LogLevelEnum::GetEnum('note'), $fmt, ...$args); } catch(\Exception $exc) { throw $exc; } }
    public function Debug   (string $fmt, ...$args) { try{ $this->Log(LogLevelEnum::GetEnum('dbg'),  $fmt, ...$args); } catch(\Exception $exc) { throw $exc; } }
    public function Trace   (string $fmt, ...$args) { try{ $this->Log(LogLevelEnum::GetEnum('trc'),  $fmt, ...$args); } catch(\Exception $exc) { throw $exc; } }

    /**
     * Logs entry
     * @param LogLevelEnum $level Log level of message
     * @param string $fmt Format
     * @param mixed $args Arguments
     */
    public function Log(LogLevelEnum $level, string $fmt, ...$args) {

        try {

            if($level->GetValue() < $this->Level->GetValue()) // exclude lower levels
                return;

            $msg = $this->InnerLog($level, $fmt, ...$args);
            $this->FileLog($msg);
            $this->DbLog();

        } catch (\Exception $exc) {

            throw $exc;
        }

    }

    /**
     * Setups file logging
     * @param string $path Log directory path
     */
    public function SetupFileLog(string $path) {

        try {

            if(!\is_dir($path) && !@\mkdir($path, 0755, true)) {

                $this->FileLogAllowed = false;
                return;
            }

            $filename = sprintf("%s.log", \now('Y-m-d'));

            $this->BaseFilePath   = $path;
            $this->Filename       = $filename;
            $this->FileLogAllowed = true;

            $this->Log(LogLevelEnum::GetEnum('info'), 'Logger.FileLog ready');
            $this->Log(LogLevelEnum::GetEnum('trc'), 'Logger.FileLog Path: %s, File: %s', $this->BaseFilePath, $this->Filename);

        } catch (\Exception $exc) {

            throw $exc;
        }
    }

    /**
     * Dumps messages
     */
    public function Dump() {

        echo '<pre>';
        foreach($this->Messages as $m) {
            echo $m->ToString();
        }
        echo '</pre>';
    }


    /**
     * @param LogLevelEnum $level Log level of message
     * @param string $fmt Format
     * @param mixed $args Arguments
     */
    private function InnerLog(LogLevelEnum $level, string $fmt, ...$args) {

        $msg = $this->PrepareMessage($level, $fmt, ...$args);
        $this->Messages->Add($msg);

        return $msg;
    }

    /**
     * @param LogEntry $entry Entry to write to file
     * @throws LoggerException
     */
    private function FileLog(LogEntry $entry) {

        if(!$this->FileLogAllowed)
            return;

        try {

            $msg      = $entry->ToString();
            $filepath = sprintf("%s/%s", $this->BaseFilePath, $this->Filename);
            $fhandle  = \fopen($filepath, "a");

            if($fhandle === false)
                throw new LoggerException("Cannot open log file: %s", $filepath);

            \fwrite($fhandle, $msg);
            \fclose($fhandle);

        } catch (\Exception $exc) {

            throw new LoggerException("FileLog error: %s", $exc->getMessage());
        }

    }

    /**
     * TODO:
     */
    private function DbLog() {

        if(!$this->DbLogAllowed)
            return;
    }

    /**
     * @param LogLevelEnum $level Log level of message
     * @param string $fmt Format
     * @param mixed $args Arguments
     * @return LogEntry
     */
    private function PrepareMessage(LogLevelEnum $level, string $fmt, ...$args) {

        $entry = new LogEntry($level, $fmt, ...$args);
        return $entry;
    }

}