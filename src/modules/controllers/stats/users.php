<?php

namespace Apikor\Modules\StatsModule;

use Apikor\Core\Controller;
use Apikor\Core\Services\UsersService;

class UsersController extends Controller {

    /**
     * Returns count of registered and valid users
     * @return array
     */
    public function Count() {

        $service = new UsersService();
        return $service->CountStats();
    }

}
