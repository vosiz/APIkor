<?php

namespace Apikor\Db;

use Vosiz\VaTools\Db\DbConnection as VaDb;
use Vosiz\VaTools\Db\DbConnectionConfig as VaDbCfg;
use Vosiz\VaTools\Structure\Credentials as Credentials;

class DbCon extends VaDb {

    // TODO:
    public function __construct(string $constr, string $user, string $pass) {

        try {

            $conconf = new VaDbCfg($constr, new Credentials($user, $pass));
            $conconf->AddAttr(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return parent::__construct($conconf);

        } catch(Exception $exc) {

            throw $exc;
        }
        
    }

    // TODO:
    public function Any(string $table, array $where = []) {

        try {

            $query = $this->Query($table);
            // basic select
            $query->Select();
            // where clause
            if(!empty($where)) {
                foreach($where as $k => $v) {
                    $query->Where($k, asarray($v));
                }
            }
            return $query->Execute();

        } catch(\Exception $exc) {

            throw new \Apikor\DbException("DB.All failed:".$exc->getMessage());
        }
    }

}
