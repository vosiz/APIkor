<?php

namespace Apikor\Output;


abstract class Formatter implements IFormat{

    const FORMATTER_KEY_VARDUMP     = 'vard';
    const FORMATTER_KEY_VAR_BROSWER = 'varb';
    const FORMATTER_KEY_NODEH       = 'node';
    const FORMATTER_KEY_JSON        = 'json';
    const FORMATTER_KEY_XML         = 'xml';
    const FORMATTER_KEY_HTML        = 'html';
    // alts
    const FORMATTER_KEY_VAR         = 'var';
    const FORMATTER_KEY_VAR_PRE     = 'pre';


    public static function Translate(string $format) {

        switch($format) {

            case self::FORMATTER_KEY_VAR:
            case self::FORMATTER_KEY_VARDUMP:
                return new VarDumpFormat();
                break;

            case self::FORMATTER_KEY_VAR_BROSWER:
            case self::FORMATTER_KEY_VAR_PRE:
                return new VarBroswerFormat();
                break;

            case self::FORMATTER_KEY_NODEH:
                return new NodehFormat();
                break;
            
            case self::FORMATTER_KEY_JSON:
                return new JsonFormat();
                break;

            case self::FORMATTER_KEY_XML:
                return new XmlFormat();
                break;

            case self::FORMATTER_KEY_HTML:
                return new HtmlFormat();
                break;

            default:
                throw new \UnimplementedStateException($format);
        }
    }
}