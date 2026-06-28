<?php

namespace Apikor\Core\Services;

use Apikor\Core\Models\UserModel;

class UsersService extends DbService {

    protected static $Table      = 'users';
    protected static $ModelClass = UserModel::class;

    /**
     * Returns count of registered and valid users
     * @return array ['registered' => int, 'valid' => int]
     */
    public function CountStats() {

        return [
            'registered' => $this->Count(true),
            'valid'      => $this->ValidCount()
        ];
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
     * @return UserModel[]
     */
    public function ByRole(int $role_id) {

        return $this->Find(['role_id' => $role_id]);
    }

    /**
     * Returns single user by email
     * @param string $email
     * @return UserModel|null
     */
    public function ByEmail(string $email) {

        return $this->By('email', $email);
    }

}
