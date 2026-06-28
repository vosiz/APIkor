<?php

namespace Apikor\Engine;

use Vosiz\Enums\Enum;

class EngineStatusEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init() {

        /**
         * [FEIS Dxxx]b
         * ==========================================================
         * - F - crash (fatal)              - 1b = fatal crash
         * - E - not working (has errors)   - 1b = errors
         * - I - initialized                - 1b = no error in init (system init)
         * - S - starts working             - 1b = indicates succesfull init (app init)
         * - D - done                       - 1b = stops working
         * - x - specifics/reserved         - specified parammeters/message
         */
        $vals = [
            'cold'      => [0x00, "Not started"],                   // 0000 0000b - not called Work
            'started'   => [0x20, "Started, not doing anything"],   // 001x xxxxb - checking, initliazed
            'working'   => [0x10, "Working"],                       // xxx1 xxxxb - starts to work
            'done'      => [0x28, "Done work, success"],            // 001x 1xxxb - everything ok, work done
            'failed'    => [0x40, "Not done, has errors"],          // x1xx xxxxb - errors
            'leak'      => [0x78, "Done with problems"],            // x111 1xxxb - working, but not ok
            'broken'    => [0x80, "Unrecoverable break"],           // 1xxx xxxxb - exception
        ];
        self::AddValues($vals);
    } 
}


class EngineStatus {

    private EngineStatusEnum $Status;
    private $Info;
    private $Logger;


    /**
     * Constructor
     */
    public function __construct() {

        $this->Logger = \Apikor\Logger::GetInstance();
        $this->StatusChange('cold');
    }


    /**
     * Engine started
     */
    public function Start() {

        $this->StatusChange('started');
    }

    /**
     * Engine is working
     */
    public function Work() {

        $this->StatusChange('working');
    }

    /**
     * Engine finnished working
     * @param array $problems Problems occured during working
     */
    public function Finish(array $problems = []) {

        if(is_noe($problems))

            $this->StatusChange('done');

        else {

            $this->StatusChange('leak');
            $this->Info = [];
            foreach($problems as $p) {
                $this->Info[] = $p;
            }
        }
            
    }

    /**
     * Engine failed but finished
     * @param array $info Info why it failed
     */
    public function Fail(array $info = []) {

        $this->StatusChange('failed');
        $this->Info = $info;
    }

    /**
     * Engine broke
     * @param ApikorException $exc Engine broke exception
     */
    public function Broke(\ApikorException $exc) {

        $this->StatusChange('broken');
        $this->Info = [];
        $this->Info[] = $exc->ToString();

    }

    /**
     * Is engine status ok
     * @return bool
     */
    public function IsOk() {

        return $this->Status->Compare(EngineStatusEnum::GetEnum('done'));
    }

    /**
     * Current engine status string representation
     * @return string[] Report
     */
    public function GetReport() {

        $msg = [];
        $msg['header'] = sprintf("ENGINE.Status: %s (%s)", $this->Status->__toString(), $this->Status->GetDisplay());
        if(!is_noe($this->Info)) {

            $msg['header2'] = "Additional data:";
            foreach($this->Info as $k => $i) {

                $msg[$k] = sprintf("%s %s", is_numeric($k) ? '' : "$k =", $i);
            }
            
        }

        return $msg;
    }


    /**
     * Changes engines status and logs it
     */
    private function StatusChange(string $status_enum_key) {

        $this->Status = EngineStatusEnum::GetEnum($status_enum_key);
        $status = $this->Status;
        $this->Logger->Info(sprintf("Engine.Status.Change: [%s] %s", $status->GetName(), $status->GetDisplay()));
    }

}