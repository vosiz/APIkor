<?php

namespace Apikor;

use \Apikor\Db\DbCon;

abstract class DbDataService extends DataService {
    
    protected DbCon $Db;
    protected string $DbId;
    protected string $DbTable;

    // TODO:
    // ========================
    // single
    // all
    // count

    // Workflow
    // SELECT
    // - create query
    // - execute select
    // - map to model
    // - return model

    public function __construct(string $table, string $dbconn_id = 'main') {

        parent::__construct();
        $this->DbId = $dbconn_id;
        $this->DbTable = $table;
        //$this->Db = \Apikor\Engine::ProvideData(EngineContainerSectionEnum::GetEnum('db'), $dbconn_id);
        //debug($this->Db);
    }

    // TODO:
    public function _Setup() {

        try {

            parent::_Setup();
            $this->Db = \Apikor\Engine::ProvideData(EngineContainerSectionEnum::GetEnum('db'), $this->DbId);
            return $this;

        } catch(\Exception $exc) {

            throw new ContainerException("Unable to setup dbdataservice: ".$exc->getMessage());
        }
        
    }

    // TODO
    public function All() {

        try {

            $rows = $this->Db->All($this->DbTable);

        } catch(\Exception $exc) {

            throw new DbException("Cannot fetch All (Db.All): ".$exc->getMessage());
        }

    }

}