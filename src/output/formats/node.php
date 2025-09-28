<?php

namespace Apikor\Output;

use \Vosiz\VaTools\Structure\NodeHierarchy as Nodeh;

class NodehFormat extends Formatter {

    public function Format($data) {

        throw new \Apikor\NotImplementedYet("Nodeh (NodeHierarchy) not supported for now");
        // $root = Nodeh::Create('ROOT');
        // $root = $this->BuildStructure($root, $data);
        // echo $this->ToString($root);
    }


    // private function BuildStructure(Nodeh $root, $data) {

    //     var_dump($root);
    //     return $root;
    // }

    // private function Attach(Nodeh $parent, Nodeh $children) {


    // }

    // private function ToString(Nodeh $root) {

    //     $output = '';
    //     $root->Next();

    //     return $output;
    // }
}