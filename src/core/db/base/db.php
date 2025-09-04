<?php

namespace Apikor\Db;

use Vosiz\VaTools\Db\DbConnection as VaDb;
use Vosiz\VaTools\Db\DbConnectionConfig as VaDbCfg;
use Vosiz\VaTools\Structure\Credentials as Credentials;

class DbCon extends VaDb {

    public function __construct(string $constr, string $user, string $pass) {

        try {

            $conconf = new VaDbCfg($constr, new Credentials($user, $pass));
            $conconf->AddAttr(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return parent::__construct($conconf);

        } catch(Exception $exc) {

            throw $exc;
        }
        
    }
}
