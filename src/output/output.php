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
    const FORMATTER_KEY_DUMP        = 'dump';


    public static function Translate(string $format) {

        switch($format) {

            case self::FORMATTER_KEY_VAR:
            case self::FORMATTER_KEY_VARDUMP:
                require_once(__DIR__.'/formats/vard.php');
                return new VarDumpFormat();
                break;

            case self::FORMATTER_KEY_VAR_BROSWER:
            case self::FORMATTER_KEY_VAR_PRE:
            case self::FORMATTER_KEY_DUMP:
                require_once(__DIR__.'/formats/varb.php');
                return new VarBroswerFormat();
                break;

            case self::FORMATTER_KEY_NODEH:
                require_once(__DIR__.'/formats/node.php');
                return new NodehFormat();
                break;
            
            case self::FORMATTER_KEY_JSON:
                require_once(__DIR__.'/formats/json.php');
                return new JsonFormat();
                break;

            case self::FORMATTER_KEY_XML:
                require_once(__DIR__.'/formats/xml.php');
                return new XmlFormat();
                break;

            case self::FORMATTER_KEY_HTML:
                require_once(__DIR__.'/formats/html.php');
                return new HtmlFormat();
                break;

            default:
                throw new \UnimplementedStateException($format);
        }
    }
}