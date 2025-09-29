<?php 

namespace Apikor\StatsModule;

// use Vosiz\VaTools\Retval;
use Apikor\Helpers\MessageCreator as CreateMsg;

class EntityController extends \Apikor\Controller {

    private $AccountService;

    // Abstract implementation
    protected function _FunctionDefinitionsSetup() {
        
        $this->AddFuncDefRules('Users');
    }

    // Abstract implementation
    protected function _Setup() {

        $this->AccountService = $this->SetupService('account');
    }

    /**
     * User statistics
     * TODO:
     */
    public function Users() {

        // get count of users
        $this->AccountService->GetStats();

        fakup("Not done yet");
    }
}