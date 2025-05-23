<?php

namespace Apikor;

use Vosiz\Utils\Collections\Collection;

class UrlParameters {

    private $Pars;  public function GetParams()     { return $this->Pars;   }

    /**
     * Constructor - params setup
     */
    public function __construct(array $params = array()) {

        $this->Pars = $params;
    }

    /**
     * Checks all requireds
     * @param array Keys required
     * @return array Not found ones
     */
    public function CheckRequired(array $req = array()) {

        $not_found = array();
        $pars = Collection::ToCollection($this->Pars);
        foreach($req as $r => $v) {

            if(!$pars->HasKey($r)) {

                $not_found[] = $r;
            }
                
        }

        return $not_found;
    }

    /**
     * Sets default values
     * @param array $optional Values which has default value
     */
    public function SetDefaults(array $optional = array()) {

        $pars = Collection::ToCollection($this->Pars);
        foreach($optional as $opt => $defv) {

            if($pars->HasKey($opt))
                continue;

            $pars->Add($defv, $opt);
        }

        $this->Pars = $pars->ToArray();
    }
}