<?php

namespace Apikor;

use \Apikor\Db\DbCon;

abstract class DbDataService extends DataService {
    
    protected DbCon $Db;
    protected string $DbId;
    protected string $DbTable;
    protected $DbModelName;

    private $DbTableMapper;
    private $DbModel;

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
    }

    // TODO:
    public function _Setup() {

        try {

            parent::_Setup();
            $this->Db = Engine::ProvideData(EngineContainerSectionEnum::GetEnum('db'), $this->DbId);
            $this->DbTableMapper = Engine::ProvideData(EngineContainerSectionEnum::GetEnum('mapper'), 'db');
            $this->DbModel = Engine::ProvideData(EngineContainerSectionEnum::GetEnum('model'), $this->DbModelName);
            return $this;

        } catch(\Exception $exc) {

            throw new ContainerException("Unable to setup dbdataservice: ".$exc->getMessage());
        }
        
    }

    // TODO
    public function All() {

        try {

            $rows = $this->Db->All($this->DbTable);
            return $this->DbTableMapper->ToModel($this->DbModel, $rows);

        } catch(\Exception $exc) {

            throw new DbException("Cannot fetch All: ".$exc->getMessage());
        }

    }

}