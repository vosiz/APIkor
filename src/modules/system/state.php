<?php

namespace Apikor\SystemModule;

use Vosiz\VaTools\Retval;
use Apikor\Helpers\MessageCreator as CreateMsg;

class StateController extends \Apikor\Controller {

    // Abstract implementation
    protected function _FunctionDefinitionsSetup() {

        $this->AddFuncDefRules('Db');
    }

    public function Db() {

        try {

            // db connection info
            $con_data = [];
            foreach(\Apikor\Engine::ProvideData(\Apikor\EngineDataContainer::SECTION_KEY_DB) as $key => $conn) {

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