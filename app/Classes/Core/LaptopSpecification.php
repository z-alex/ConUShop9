<?php

namespace App\Classes\Core;

class LaptopSpecification extends ComputerSpecification {
    private $displaySize;
    private $dimension;
    private $batteryInfo;

    function __construct($data) {
        parent::__construct($data);
        $this->set($data);
    }
    
    public function set($data) {
        if (isset($data->displaySize)) {
            $this->displaySize = $data->displaySize;
        }
        
        if (isset($data->dimension)) {
            $this->dimension = $data->dimension;
        }
        
        if (isset($data->batteryInfo)) {
            $this->batteryInfo = $data->batteryInfo;
        }
        
        parent::set($data);
    }
    
    public function get() {
        $returnData = parent::get();
        
        $returnData->displaySize = $this->displaySize;
        $returnData->dimension = $this->dimension;
        $returnData->batteryInfo = $this->batteryInfo;
        
        return $returnData;
    }
    
}
