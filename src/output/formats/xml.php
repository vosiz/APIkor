<?php

namespace Apikor\Output;

use \VaTools\Format\XmlBuilder;
use \VaTools\Format\Xml\XmlElement;
use \Apikor\Response\Response;
use \Vosiz\Enums\Enum;



class ToXml extends XmlBuilder {

    /**
     * Creates XML from object
     * @param object $object Class instance to process
     * @return Apikor\Output\ToXml (Formatter)
     */
    public static function FromObject(object $object) {

        $xml = new ToXml(self::XmlObjectName($object));
        $xml->Parse($object);
        return $xml;        
    }

    /**
     * Gets type of object as name
     * @param mixed $object anything
     * @return string
     * @throws \Exception
     */
    private static function XmlObjectName($object) {

        try {

            $type = typeof($object);
            $splits = explode("\\", $type);
            $last = end($splits);
    
            return $last;

        } catch(\Exception $exc) {

            throw $exc;
        }

    }


    /**
     * Debug print
     */
    public function DebugPrint() {

        var_dump($this);
        $output = $this->Render();
        var_dump($output);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($output);

        echo "<pre>" . htmlspecialchars($dom->saveXML()) . "</pre>";
    }


    /**
     * Parsing root object to XML
     * @param object $root_object Root object
     * @throws \Apikor\OutputFormatterException
     */
    private function Parse(object $root_object) {

        try {

            foreach(getprops($root_object) as $prop) {

                $this->ProcessNode($this->Root, $prop);
            }
            
        } catch(\Exception $exc) {

            throw new \Apikor\OutputFormatterException("XmlFormat ", $exc->getMessage());
        }
  
    }

    /**
     * Process node to XML structure
     * @param XmlElement &$parent Parent node
     * @param mixed $object Object to convert (with children/properties)
     * @param bool $is_property Process like prop array (getprops function output)
     * @throws \Exception
     */
    private function ProcessNode(XmlElement &$parent, $object, bool $is_property = true) {

        try {

            if($is_property) {

                $name = $object['name'];
                $value = $object['value'];
                $type = $object['type'];
                $namespace = typeof($value) !== "NULL" ? typeof($value) : null;

            } else {

                $name = self::XmlObjectName($object);
                $value = $object;
                $type = 'scalar';
                $namespace = typeof($object) !== "NULL" ? typeof($object) : null;
            }

            $el = new XmlElement($name);
            if(!is_noe($namespace))
                $el->AddAtts(['ns' => $namespace]);

            if(\is_object($value)) { // object

                if($value instanceof Enum) { // Enum object

                    $k = $value->GetKey();
                    $v = $value->GetValue();
                    $el->SetText($v);
                    $el->AddAtts(['key' => $k]);

                } else { // others

                    foreach(getprops($value) as $prop) {

                        $this->ProcessNode($el, $prop);
                    }
                }
                
            } else if(\is_array($value)) { // array

                foreach($value as $v) {

                    var_dump($v);
                    $this->ProcessNode($el, $v, false);
                }

            } else { // scalar

                //var_dump($value);
                if(!\is_noe($value)) {

                    if('public' == $type || 'scalar' == $type) {

                        $el->SetText($value);

                    } else {

                        $el->AddAtts([$name => $value]);
                    }
                } 
            }

            $el->SetParent($parent);

        } catch(\Exception $exc) {

            throw $exc;
        }

    }

}

class XmlFormat extends Formatter {

    /**
     * Abstract implementation
     */
    public function Format($data) {
        
        try {

            $xml = ToXml::FromObject($data);
            $output = $xml->Render();
            $xml->DebugPrint();

        } catch(\Exception $exc) {

            throw $exc;
        }

    }
}