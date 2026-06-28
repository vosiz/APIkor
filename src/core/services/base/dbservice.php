<?php

namespace Apikor\Core\Services;

abstract class DbService extends DataService {

    protected static $Table      = null;
    protected static $ModelClass = null;

    protected $Db;

    /**
     * Constructor - pulls DB connection from container
     */
    public function __construct() {

        $this->Db = \Apikor\Engine::GetInstance()->GetContainer()->Get('db');
    }

    /**
     * Returns all records, keyed by id
     * @param bool $active_only
     * @return array [id => Model]
     */
    public function All(bool $active_only = true) {

        $qb = $this->Db->Query(static::$Table)->Select();

        if($active_only)
            $qb->Where('active = ?', [1]);

        return $this->ToModels($qb->Execute());
    }

    /**
     * Returns count of records
     * @param bool $active_only
     * @return int
     */
    public function Count(bool $active_only = true) {

        $qb = $this->Db->Query(static::$Table)->Select(['COUNT(*) AS count']);

        if($active_only)
            $qb->Where('active = ?', [1]);

        $result = $qb->Execute();
        return $result ? (int)$result[0]->count : 0;
    }

    /**
     * Finds records by conditions
     * @param array $where [column => value]
     * @param string|array|null $order Column name or [column => ASC|DESC]
     * @param int|null $limit
     * @return array [id => Model]
     */
    public function Find(array $where, $order = null, ?int $limit = null) {

        $qb = $this->Db->Query(static::$Table)->Select();
        $this->ApplyWhere($qb, $where);

        if($order !== null)  $qb->OrderBy($order);
        if($limit !== null)  $qb->Limit($limit);

        return $this->ToModels($qb->Execute());
    }

    /**
     * Updates records matching conditions
     * @param array $data [column => value]
     * @param array $where [column => value]
     * @return bool
     */
    public function Update(array $data, array $where) {

        $qb = $this->Db->Query(static::$Table)->Update($data);
        $this->ApplyWhere($qb, $where);

        return $qb->Execute();
    }

    /**
     * Soft deletes records matching conditions (sets active = 0)
     * @param array $where [column => value]
     * @return bool
     */
    public function Delete(array $where) {

        return $this->Update(['active' => 0], $where);
    }

    /**
     * Inserts a record
     * @param array $data [column => value]
     * @return int Inserted ID
     */
    public function Insert(array $data) {

        $this->Db->Query(static::$Table)->Insert($data)->Execute();
        return (int)$this->Db->LastInsertId();
    }

    /**
     * Returns first record matching conditions
     * @param array $where [column => value]
     * @param string|array|null $order
     * @return mixed Model or null
     */
    public function One(array $where, $order = null) {

        $results = $this->Find($where, $order, 1);
        return !empty($results) ? reset($results) : null;
    }

    // Aliases
    public function Where(array $where, $order = null, ?int $limit = null) { return $this->Find($where, $order, $limit); }
    public function By(string $column, $value)                                    { return $this->One([$column => $value]);      }
    public function ById(int $id)                                                  { return $this->One(['id' => $id]);            }

    /**
     * Maps DB rows to keyed model array [id => Model]
     * @param array $rows
     * @return array
     */
    protected function ToModels(array $rows) {

        $models = [];
        foreach($rows as $row) {

            $model = call_user_func([static::$ModelClass, 'Create'], $row);
            $models[$model->GetId()] = $model;
        }
        return $models;
    }

    /**
     * Applies where conditions to query builder
     * @param \Vosiz\VaTools\Db\QueryBuilder $qb
     * @param array $where [column => value]
     */
    private function ApplyWhere(\Vosiz\VaTools\Db\QueryBuilder $qb, array $where) {

        foreach($where as $column => $value) {

            $qb->Where("$column = ?", [$value]);
        }
    }

}
