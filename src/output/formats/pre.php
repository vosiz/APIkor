<?php

namespace Apikor\Output;

use Apikor\Response\Response;

class PreFormat extends Formatter {

    /**
     * Formats response as human-readable pre-formatted output
     * @param Response $response
     * @return string
     */
    public function Format(Response $response): string {

        ob_start();
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        return ob_get_clean();
    }

}
