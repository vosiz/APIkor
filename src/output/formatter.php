<?php

namespace Apikor\Output;

use Apikor\Response\Response;

interface IFormat {

    /**
     * Formats response to output string
     * @param Response $response
     * @return string
     */
    public function Format(Response $response): string;
}

abstract class Formatter implements IFormat {

    /**
     * Resolves format key to formatter instance
     * @param string $format Format key from URL
     * @return Formatter
     * @throws \Exceptionf
     */
    public static function Resolve(string $format) {

        switch($format) {

            case 'xml':
                return new XmlFormat();

            case 'var':
                return new VarFormat();

            default:
                throw new \Exceptionf("Unknown output format: %s", $format);
        }
    }

}
