<?php

namespace Apikor\Services;

use \Apikor\Models\UsersStatsModel;

class UserService extends \Apikor\DbDataService  {

    protected $DbModelName = 'user';

    // http://localhost/apikor/v1/xml/stats/entity/users
    public function Stats() {

        try {

            //$stats = \Apikor\Engine::ProvideData(\Apikor\EngineContainerSectionEnum::GetEnum()) // proper way, but whatever
            $stats = new UsersStatsModel();
            $stats->Count = count($this->All());
            $stats->ActiveCount = count($this->AllActive());

            return $stats;

        } catch (\Exception $exc) {

            throw $exc;
        }
    }
}