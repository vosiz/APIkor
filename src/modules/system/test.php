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
        $this->AddFuncDefRules('Fakup');
        $this->AddFuncDefRules('Fatal', [
            \Apikor\FunctionDefinitionRule::Default('msg', "Fatal error")
        ]);
        $this->AddFuncDefRules('TextArray');
    }

    // Abstract implementation
    protected function _Setup() { }

    /**
     * Aloha function
     * @return Response\Message (Text)
     */
    public function Aloha() {

        return CreateMsg::PlainText("Aloha!");
    }

    /**
     * Retval test
     * - required: type
     * - default: msg
     * @return Response\Message (retval)
     */
    public function Retval() {

        $get = $this->GetParams();
        \extract($get);

        return CreateMsg::Retval($Type, $Msg);
    }

    /** 
     * Fakup exception test
     * @return Response\Message
     */
    public function Fakup() {

        try {

            fakup("This is fakup %s test", "FK");

        } catch (\Apikor\FakupException $exc) {

            throw $exc;
        }
        catch (Exception $exc) {

            throw $exc;
        }
    }

    /**
     * Fatal error test
     * - default: msg
     */
    public function Fatal() {

        try {

            $get = $this->GetParams();
            fatal(htmlspecialchars($get['Msg']));

        } catch (\Apikor\FatalErrorException $exc) {

            throw $exc;
        }
        catch (Exception $exc) {

            throw $exc;
        }
    }

    /**
     * Text array test
     */
    public function TextArray() {

        try {

            $a = [123, 1.2, "string", true, new \StdClass()];
            return CreateMsg::TextArray($a);

        } catch(\Exception $exc) {

            throw $exc;
        }

    }
}