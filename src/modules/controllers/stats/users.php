<?php

namespace Apikor\Modules\StatisticsModule;

use Apikor\Core\Controller;

class UsersController extends Controller {

    /**
     * Returns count of registered and valid users
     * @return array
     */
    public function Count() {

        return [
            'registered' => 0,
            'valid'      => 0
        ];
    }

}
