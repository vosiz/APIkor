<?php

namespace Apikor\Core;

abstract class Controller {

    /**
     * Finds action method by name and version
     * - v1 always resolves to base action (no suffix)
     * - vN tries ActionN, ActionN-1, ..., Action2, then Action
     * @param string $action Action name (CamelCase)
     * @param int $version API version
     * @return string Resolved method name
     * @throws \Apikor\Engine\NotFoundException
     */
    public function FindAction(string $action, int $version) {

        for($v = $version; $v >= 2; $v--) {

            $method = $action . $v;
            if(method_exists($this, $method))
                return $method;
        }

        if(method_exists($this, $action))
            return $action;

        throw new \Apikor\Engine\NotFoundException("Action '%s' (v%d) not found in %s", $action, $version, get_class($this));
    }

}
