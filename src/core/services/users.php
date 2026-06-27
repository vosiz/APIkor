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
    public function CountStats(): array {

        return [
            'registered' => $this->Count(false),
            'valid'      => $this->Count(true)
        ];
    }

    /**
     * Returns users by role
     * @param int $role_id
     * @return UserModel[]
     */
    public function ByRole(int $role_id): array {

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
