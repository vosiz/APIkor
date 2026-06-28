<?php

namespace Apikor\Core\Services;

use Apikor\Core\Models as Models;

class UsersService extends DbService {

    protected static $Table      = 'users';
    protected static $ModelClass = Models\UserModel::class;

    /**
     * Returns registered and valid user counts as model
     * @return Models\StatsUsersModel
     */
    public function CountStats() {

        $model = new Models\StatsUsersModel();
        $model->RegisteredCount = $this->Count(true);
        $model->ValidCount      = $this->ValidCount();
        return $model;
    }

    /**
     * Returns count of validated users (valid = 1, active = 1)
     * @return int
     */
    public function ValidCount() {

        $result = $this->Db->Query(static::$Table)
            ->Select(['COUNT(*) AS count'])
            ->Where('active = ?', [1])
            ->AndWhere('valid = ?', [1])
            ->Execute();

        return $result ? (int)$result[0]->count : 0;
    }

    /**
     * Returns users by role
     * @param int $role_id
     * @return Models\UserModel[]
     */
    public function ByRole(int $role_id) {

        return $this->Find(['role_id' => $role_id]);
    }

    /**
     * Returns single user by email
     * @param string $email
     * @return Models\UserModel|null
     */
    public function ByEmail(string $email) {

        return $this->By('email', $email);
    }

}
