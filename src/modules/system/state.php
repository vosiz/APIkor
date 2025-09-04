<?php

// namespace SampleProject\UBC\CRM\SystemModule;
namespace Apikor\SystemModule;

use Vosiz\VaTools\Retval;
use Apikor\Helpers\MessageCreator as CreateMsg;

class StateController extends \Apikor\Controller {

    // Abstract implementation
    protected function _FunctionDefinitionsSetup() {

        $this->AddFuncDefRules('Db');
        // $this->AddFuncDefRules('Aloha');
        // $this->AddFuncDefRules('Retval', [
        //     \Apikor\FunctionDefinitionRule::Required('type'),
        //     \Apikor\FunctionDefinitionRule::Default('msg', "Undefined message")
        // ]);
        // $this->AddFuncDefRules('Fakup');
        // $this->AddFuncDefRules('Fatal', [
        //     \Apikor\FunctionDefinitionRule::Default('msg', "Fatal error")
        // ]);
    }

    public function Db() {

        try {

            // connected info
            $con_data = [];
            foreach(\Apikor\Engine::ProvideData('db') as $key => $conn) {

                $a = array();
                $info = $conn->ConnInfo();
                $a['data'] = $info->AsArray();
                $a['desc'] = $info->AsString();
                $con_data[$key] = $a;
            }

            return CreateMsg::TextArray($con_data);

        } catch(\Exception $exc) {

            throw $exc;
        }

    }

}