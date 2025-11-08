<?php 

namespace Apikor\StatsModule;

// use Vosiz\VaTools\Retval;
use Apikor\Helpers\MessageCreator as CreateMsg;

class EntityController extends \Apikor\Controller {

    private $AccountService;
    private $UserService;

    // Abstract implementation
    protected function _FunctionDefinitionsSetup() {
        
        $this->AddFuncDefRules('Users');
    }

    // Abstract implementation
    protected function _Setup() {

        $this->UserService = $this->SetupService('user');
        //$this->AccountService = $this->SetupService('account');
    }

    /**
     * User statistics
     * TODO:
     */
    public function Users() {

        //$users = $this->UserService->All();
        $stats = $this->UserService->Stats();

        return CreateMsg::Models($stats);
    }
}