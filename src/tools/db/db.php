<?php

namespace Apikor\Tools;

use Vosiz\VaTools\Db\DbConnection      as VaDbConnection;
use Vosiz\VaTools\Db\DbConnectionConfig as VaDbConnectionConfig;
use Vosiz\VaTools\Db\DbException;

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

    /**
     * Executes raw SQL with optional parameters
     * @param string $sql
     * @param array $params
     * @return bool
     * @throws DbException
     */
    public function Raw(string $sql, array $params = []) {

        try {

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);

        } catch(\PDOException $exc) {

            throw new DbException($exc->getMessage());
        }
    }

}
