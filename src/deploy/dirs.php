<?php

namespace Apikor\Deploy;

class Dirs {

    /**
     * Returns default directories required by apikor
     * @param string $base Project base path
     * @return array
     */
    public static function Get(string $base) {

        $base = rtrim($base, '/\\');
        return [
            $base . '/logs',
        ];
    }

}
