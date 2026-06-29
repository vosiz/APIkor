<?php

namespace Apikor\Engine;

class Config {

    private $Data = [];

    /**
     * Sets configuration value
     * @param string $key Dotted key (e.g. 'format.default')
     * @param mixed $value
     * @return Config
     */
    public function Set(string $key, $value) {

        $this->Data[$key] = $value;
        return $this;
    }

    /**
     * Sets multiple values at once
     * @param array $cfg [key => value]
     * @return Config
     */
    public function SetBulk(array $cfg) {

        foreach($cfg as $key => $value)
            $this->Set($key, $value);

        return $this;
    }

    /**
     * Returns configuration value
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function Get(string $key, $default = null) {

        return $this->Data[$key] ?? $default;
    }

    /**
     * Checks if key exists
     * @param string $key
     * @return bool
     */
    public function Has(string $key) {

        return isset($this->Data[$key]);
    }

    /**
     * Validates all configuration — checks required keys, then validates values
     * @throws ConfigException
     */
    public function Validate() {

        $errors = [];

        $required = ['format.default'];
        foreach($required as $key) {

            if(!$this->Has($key))
                $errors[] = sprintf("Missing required key: '%s'", $key);
        }

        if($this->Has('format.default')) {

            $err = $this->ValidateFormatDefault();
            if($err !== null)
                $errors[] = $err;
        }

        if(!empty($errors))
            throw new ConfigException("Config validation failed: %s", implode('; ', $errors));
    }


    /**
     * @return string|null Error message or null on success
     */
    private function ValidateFormatDefault() {

        $value = $this->Get('format.default');

        if(!in_array($value, \Apikor\Output\Formatter::FORMATS))
            return sprintf("'format.default' has invalid value '%s', valid: %s", $value, implode(', ', \Apikor\Output\Formatter::FORMATS));

        return null;
    }

}
