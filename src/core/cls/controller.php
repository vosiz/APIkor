<?php

namespace Apikor;

use Apikor\UrlParameters;
use Vosiz\Utils\Collections\Collection;
use Vosiz\Enums\Enum;
use Vosiz\VaTools\Retval;

class FunctionDefinitionRuleEnum extends Enum{

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'required'  => 0x00,
            'default'   => 0x01,
        ];
        self::AddValues($vals);
    } 
}


class FunctionDefinitionRule {

    private $RuleType;  public function GetType()   { return $this->RuleType;   }
    private $Name;      public function GetName()   { return $this->Name;       }
    private $Value;     public function GetValue()  { return $this->Value;      }

    /**
     * Locked constructor
     */
    protected function __construct(FunctionDefinitionRuleEnum $type, string $name, $value = null) {

        $this->RuleType = $type;
        $this->Name = $name;
        $this->Value = is_null($value) ? "" : $value;
    }

    /**
     * Macro for required definition rule
     * @param mixed $key Parameter name
     * @return FunctionDefinitionRule
     */
    public static function Required($key) {

        return new FunctionDefinitionRule(FunctionDefinitionRuleEnum::GetEnum('required'), $key);
    }

    /**
     * Macro for definition rule with default value
     * @param mixed $key Parameter name
     * @param mixed $value Parameter default value
     * @return FunctionDefinitionRule
     */
    public static function Default($key, $value = null) {

        return new FunctionDefinitionRule(FunctionDefinitionRuleEnum::GetEnum('default'), $key, $value);
    }
}


abstract class Controller {

    protected $UrlGetParameters;
    protected $FuncDefs = null;

    abstract protected function _FunctionDefinitionsSetup();
    abstract protected function _Setup();

    /**
     * Constructor
     * @param array $get GET parameters
     */
    public function __construct(array $get = array()) {

        try {
        
            $this->UrlGetParameters = new UrlParameters($get);
            $this->FuncDefs = array();
            $this->_FunctionDefinitionsSetup();
            $this->_Setup();

        } catch(\Exception $exc) {

            throw $exc;
        }
        
    }

    /**
     * Overrides ToString
     */
    public function __toString() {

        return get_class($this);
    }

    /** Looks for action 
     * @param string $action Action name
     * @param int $version Action suffix (default empty string)
     * @return string Action/method name
     * @throws Exception
    */
    public function FindAction(string $action, int $version = null) {

        $action = camel_str($action); // action is always UPPER camle case, deifnition can be snake_case
        $functions = get_class_methods($this);
        if ($version !== null) {

            for ($v = $version; $v >= 1; $v--) {

                $method = $action.$v;
                if (in_array($method, $functions)) {
                    return $method;
                }
            }
        }

        if (in_array($action, $functions)) {
            return $action;
        }

        throw new \Exception("Action '{$action}' (v{$version}) not found in " . get_class($this));
    }

    /**
     * Get action registered rules
     * @param string $action_name Name of action
     * @return FunctionDefinitionRule[] 
     * @throws Exception
     */
    public function ActionCheck(string $action_name) {

        try {

            $action_index = snake($action_name);
            if(is_noe($this->FuncDefs)) 
                throw new EngineWorkException("Functions defitions are not defined/setup");
            
            $c = Collection::ToCollection($this->FuncDefs);
            if(!$c->HasKey($action_name)) 
                throw new EngineWorkException("Definition for $action_index is not found");
                
            $rules = $this->FuncDefs[$action_index];
            return $rules;
            
        } catch(\Exception $exc) {

            throw $exc;
        }

    }

    /**
     * Apply and check if requirements of rules are met
     * @param FunctionDefinitionRule[] $rules
     * @throws Exception|Exceptionf
     */
    public function ApplyActionRules(array $rules = array()) {

        try  {

            $errors = array();
            $pars = $this->GetParams(true, false);
            foreach($rules as $r) {

                switch($r->GetType()->GetValue()) {

                    case FunctionDefinitionRuleEnum::GetEnum('required')->GetValue():
                        $errors = array_merge($errors, $pars->CheckRequired([ucfirst($r->GetName()) => ""]));
                        break;

                    case FunctionDefinitionRuleEnum::GetEnum('default')->GetValue():
                        $pars->SetDefaults([$r->GetName() => $r->GetValue()]);
                        break;
                    
                    default:
                        throw new \Exception("Unimplemented type of rule");
                }
            }

            if(count($errors) > 0) {

                throw new \Exceptionf("Application of rules failed, errors: %s", implode(",", $errors));
            }

        } catch (\Exception $exc) {

            throw $exc;
        }

        
    }

    /**
     * Get URL params
     * @param bool $get True uses GET, false uses POST
     * @param bool $as_array True assoc array, false Params class
     * @param mixed $key Instead of all gets particular one
     * @return mixed
     * @throws Exception
     */
    protected function GetParams(bool $get = true, bool $as_array = true, $key = null) {

        try {

            $target = $get ? $this->UrlGetParameters : $this->UrlPostParameters;
            $target = $as_array ? $target->GetParams() : $target;
            if($key != null && !$as_array) {
                $target = $target[$key];
            }
            return $target;

        } catch (\Exception $exc) {

            throw $exc;
        }

        
    }
    
    /**
     * Registers definition rule for action
     * @param string $action Action name
     * @param FunctionDefinitionRule[] $rules Action definition rules
     * @throws Exception
     */
    protected function AddFuncDefRules(string $action, array $rules = array()) {

        try  {

            $action = snake($action);
            $this->FuncDefs[$action] = [];
            foreach($rules as $r) {
    
                $this->FuncDefs[$action][] = $r;         
            }

        } catch (\Exception $exc) {

            throw $exc;
        }
    }

    /** TODO: */
    protected function SetupService(string $service) {

        try {

            return \Apikor\Engine::ProvideData('service', $service);

        } catch(\Exception $exc) {

            throw $exc;
        }
    }

}