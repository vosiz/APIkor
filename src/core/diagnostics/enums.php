<?php

namespace Apikor;

use Vosiz\Enums\Enum as Enum;

class EngineDiagnosticsLevelEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'debug'     => [0x00, 'DBG'],
            'info'      => [0x10, 'INF'],
            'warn'      => [0x20, 'WRN'],
            'error'     => [0x30, 'ERR'],
            'fatal'     => [0x40, 'FTL'],
            'exc'       => [0x41, 'EXC'],
        ];
        self::AddValues($vals);
    } 
}
