<?php

namespace Apikor\Db;

require_once(__DIR__.'/base/db.php');

class DbConMySql extends DbCon {

    public function __construct(string $constr, string $user, string $pass) {

        try {

            return parent::__construct($constr, $user, $pass);

        } catch(\Exception $exc) {

            throw $exc;
        }
        
    }
}