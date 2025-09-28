<?php 

namespace Apikor\StatsModule;

// use Vosiz\VaTools\Retval;
use Apikor\Helpers\MessageCreator as CreateMsg;


class EntityController extends \Apikor\Controller {

    // Abstract implementation
    protected function _FunctionDefinitionsSetup() {
        $this->AddFuncDefRules('Users');
    }

    /**
     * User statistics
     * TODO:
     */
    public function Users() {

        // get count of users

        fakup("Not done yet");
    }
}