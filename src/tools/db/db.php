<?php

namespace Apikor\Tools;

use Vosiz\VaTools\Db\DbConnection      as VaDbConnection;
use Vosiz\VaTools\Db\DbConnectionConfig as VaDbConnectionConfig;

class DbConnection extends VaDbConnection {

    /**
     * Constructor
     * @param VaDbConnectionConfig $cfg
     */
    public function __construct(VaDbConnectionConfig $cfg) {

        parent::__construct($cfg);
    }

    /**
     * Returns last inserted ID
     * @return int
     */
    public function LastInsertId() {

        return (int)$this->pdo->lastInsertId();
    }

}
