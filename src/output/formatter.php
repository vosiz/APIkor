<?php

namespace Apikor\Output;

use Apikor\Response\Response;

interface IFormat {

    /**
     * Formats response to output string
     * @param Response $response
     * @return string
     */
    public function Format(Response $response);
}

abstract class Formatter implements IFormat {

    const FORMATS = ['html', 'pre', 'var', 'xml'];


    /**
     * Resolves format key to formatter instance
     * @param string $format Format key from URL
     * @return Formatter
     * @throws \Exceptionf
     */
    public static function Resolve(string $format) {

        switch($format) {

            case 'html':
                return new HtmlFormat();

            case 'pre':
                return new PreFormat();

            case 'var':
                return new VarFormat();

            case 'xml':
                return new XmlFormat();

            default:
                throw new \Exceptionf("Unknown output format: %s", $format);
        }
    }

}
