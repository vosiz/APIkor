<?php

namespace Apikor\SystemModule;

use Apikor\Helpers\MessageCreator as CreateMsg;

class InfoController extends \Apikor\Controller {

    private $SettingsService;

    // Abstract implementation
    protected function _FunctionDefinitionsSetup() {

        $this->AddFuncDefRules('Settings');
    }

    // Abstract implementation
    protected function _Setup() { 

        $this->SettingsService = $this->SetupService('settings');
    }

    /**
     * Returns info about settings
     * @return Response\Message
     */
    public function Settings() {

        try {

            $settings = $this->SettingsService->All();
            
            return CreateMsg::Models($settings);
          
        } catch(\Exception $exc) {

            throw $exc;
        }

    }
}