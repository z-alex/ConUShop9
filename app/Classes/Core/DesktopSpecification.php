<?php

namespace App\Classes\Core;

class DesktopSpecification extends ComputerSpecification {
    private $dimension;
    
    function __construct($data) {
        parent::__construct($data);
        $this->set($data);
    }
    
    public function set($data) {
        if (isset($data->dimension)) {
            $this->dimension = $data->dimension;
        }
        
        parent::set($data);
    }
    
    public function get() {
        $returnData = parent::get();
        
        $returnData->dimension = $this->dimension;
        
        return $returnData;
    }
    
}
