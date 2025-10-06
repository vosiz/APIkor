<?php

namespace Apikor\Models;

class UserModel extends \Apikor\DbModel { }

class UsersStatsModel extends \Apikor\MapModel {

    public $Count;
    public $ActiveCount;
}
// TODO: user stat model, maybe with own mapper
