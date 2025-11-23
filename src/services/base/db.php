<?php

namespace Apikor;

use \Apikor\Db\DbCon;

// Class which services one! DB table
// - converts to specific one! DbModel
abstract class DbDataService extends DataService {
    
    const APIKOR_ENTITY_PREFIX  = "apikor";
    const APP_ENTITY_PREFIX     = "app";
    const ENUM_ENTITY_PREFIX    = "enum";

    const DEFAULT_MODEL_NAME    = "raw";

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

    /**
     * Return raw table name
     * @param string $table Table name
     * @param string $prefix Prefix string
     * @return string Raw prefixed table name
     */
    public static function RawTableName(string $table, string $prefix = self::APIKOR_ENTITY_PREFIX) {

        return sprintf("%s_%s", $prefix, $table);
    }

    /**
     * Return table name of apikor table
     * @param string $table Table name
     * @return string Real table name
     */
    public static function TableName(string $table) {

        return sprintf("%s_%s_%s", self::APIKOR_ENTITY_PREFIX, self::APP_ENTITY_PREFIX, $table);
    }

    /**
     * Return table name of apikor application related table
     * @param string $table Table name
     * @return string Real table name
     */
    public static function AppTableName(string $table) {

        return sprintf("%s_%s_%s", self::APIKOR_ENTITY_PREFIX, self::APP_ENTITY_PREFIX, $table);
    }

    // TODO: enum table name


    /** 
     * Constructor
     */
    public function __construct(string $table_name, string $model_name = self::DEFAULT_MODEL_NAME, string $dbconn_id = 'main') {

        //parent::__construct();
        $this->DbId = $dbconn_id;
        $this->DbTable = $table_name;
        $this->DbModelName = $model_name;
    }

    /**
     * Setups neccesary things (Model, mapper, dbconnection,...) which was pre-setup in constructor
     * @return DbDataService
     * @throws ContainerException
     */
    public function _Setup() {

        try {

            parent::_Setup();
            $this->Db = Engine::ProvideData(EngineContainerSectionEnum::GetEnum('db'), $this->DbId);
            $this->DbTableMapper = Engine::ProvideData(EngineContainerSectionEnum::GetEnum('mapper'), 'db');
            $this->DbModel = ($this->DbModelName !== self::DEFAULT_MODEL_NAME) ?
                Engine::ProvideData(EngineContainerSectionEnum::GetEnum('model'), $this->DbModelName) : NULL;
            
            return $this;

        } catch(\Exception $exc) {

            throw new ContainerException("Unable to setup dbdataservice: ".$exc->getMessage());
        }
        
    }


    /**
     * Retunrs all records from db as an array of DbModels
     * @return DbModel[]
     * @throws DbException
     */
    public function All() {

        try {

            $rows = $this->Db->Any($this->DbTable);
            return $this->DbTableMapper->ToModel($this->DbModel, $rows);

        } catch(\Exception $exc) {

            throw new DbException("Cannot fetch All: ".$exc->getMessage());
        }

    }

    /**
     * Returns all active records as an array of DbModels
     * @return DbModel[]
     * @throws DbException
     */
    public function AllActive() {

        try {

            if($this->DbModel === NULL)
                throw new FakupException("Calling AllActive on non-DbModel mapper-based service (probably raw data table; no 'active' column)");

            $rows = $this->Db->Any($this->DbTable, ['active' => true]);
            return $this->DbTableMapper->ToModel($this->DbModel, $rows);

        } catch(\Exception $exc) {

            throw new DbException("Cannot fetch All: ".$exc->getMessage());
        }

    }

    // TODO:
    /**
     * Gets values by column name
     */
    public function GetBy(string $column, $value) {

        // get and return
    }

    // TODO: single

    // TODO: count

    // TODO: filter (where, limit, order)

}