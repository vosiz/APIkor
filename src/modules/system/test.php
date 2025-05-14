<?php 

namespace Apikor\SystemModule;

use Vosiz\VaTools\Retval;
use Apikor\Helpers\MessageCreator as CreateMsg;

class TestController extends \Apikor\Controller {

    // Abstract implementation
    protected function _FunctionDefinitionsSetup() {

        $this->AddFuncDefRules('Aloha');
        $this->AddFuncDefRules('Retval', [
            \Apikor\FunctionDefinitionRule::Required('type'),
            \Apikor\FunctionDefinitionRule::Default('msg', "Undefined message")
        ]);
    }

    /**
     * Aloha function
     */
    public function Aloha() {

        return CreateMsg::PlainText("Aloha!");
    }

    /**
     * Retval test
     * - required Type
     * - default Message = ""
     */
    public function Retval() {

        $get = $this->GetParams();
        \extract($get);

        return CreateMsg::Retval($Type, $Msg);
    }
}