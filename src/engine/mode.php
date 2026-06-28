<?php

namespace Apikor\Engine;

use Vosiz\Enums\Enum;

class EngineModeEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        /**
         * [Pxxx xxDL]b
         * ==========================================================
         * P - production:  1 - production version, 0 = dev
         * D - debug mode:  1 - allowed
         * L - local:       1 - localhost build
         */
        $vals = [
            'prod'  => 0x80, // 1000 0000
            'diag'  => 0x03, // 0000 0011
            'dev'   => 0x01, // 0000 0001
        ];
        self::AddValues($vals);
    } 


    /**
     * Is debug mode
     * @return bool
     */
    public function IsDebug() {

        $v = $this->GetValue();
        return \bitmask($v, 0x02);
    }

}