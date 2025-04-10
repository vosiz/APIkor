<?php

namespace Apikor;

class Controller {

    /**
     * Overrides ToString
     */
    public function __toString() {

        return get_class($this);
    }

    /** TODO: */
    public function FindAction(string $action, int $version = null) {

        $functions = get_class_methods($this);
        if ($version !== null) {

            for ($v = $version; $v >= 1; $v--) {

                $method = $action.$v;
                if (in_array($method, $functions)) {

                    return $method;
                }
            }
        }

        if (in_array($action, $functions)) {
            return $this->$action();
        }

        throw new \Exception("Action '{$action}' (v{$version}) not found in " . get_class($this));
    }

    public function RespMessage(string $fmt, ...$pars) {

        
    }
}