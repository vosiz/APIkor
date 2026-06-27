<?php

namespace Apikor\Engine;

use Apikor\Core\Mappers\EntityDbMapper;
use Apikor\Core\Mappers\EnumDbMapper;

class Container {

    private $Instances = [];

    /**
     * Constructor - registers built-in instances
     */
    public function __construct() {

        $this->Register('entity', new EntityDbMapper());
        $this->Register('enum',   new EnumDbMapper());
    }

    /**
     * Registers instance under key
     * @param string $key
     * @param mixed $instance
     */
    public function Register(string $key, $instance) {

        $this->Instances[$key] = $instance;
    }

    /**
     * Returns instance by key
     * @param string $key
     * @return mixed|null
     */
    public function Get(string $key) {

        return $this->Instances[$key] ?? null;
    }

    /**
     * Checks if key is registered
     * @param string $key
     * @return bool
     */
    public function Has(string $key): bool {

        return isset($this->Instances[$key]);
    }

}
