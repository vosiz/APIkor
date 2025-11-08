<?php

namespace Apikor\SystemModule;

use Apikor\Helpers\MessageCreator as CreateMsg;

class InstallController extends \Apikor\Controller {

    // Abstract implementation
    protected function _FunctionDefinitionsSetup() {

        $this->AddFuncDefRules('Base');
    }

    // Abstract implementation
    protected function _Setup() { }

    /**
     * Installation o base things - is already done
     * @return Response\Message
     */
    public function Base() {

        return CreateMsg::PlainText("Base installation already done");
    }
}