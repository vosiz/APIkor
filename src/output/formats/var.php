<?php

namespace Apikor\Output;

use Apikor\Response\Response;

class VarFormat extends Formatter {

    /**
     * Formats response as var_dump output
     * @param Response $response
     * @return string
     */
    public function Format(Response $response): string {

        ob_start();
        var_dump($response);
        return ob_get_clean();
    }

}
