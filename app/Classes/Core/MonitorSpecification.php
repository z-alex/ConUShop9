<?php

namespace App\Classes\Core;

class MonitorSpecification extends ElectronicSpecification {
    private $displaySize;
    
    function __construct($data) {
        parent::__construct($data);
        $this->set($data);
    }
    
    public function set($data) {
        if (isset($data->displaySize)) {
            $this->displaySize = $data->displaySize;
        }
        
        parent::set($data);
    }
    
    public function get() {
        $returnData = parent::get();
        
        $returnData->displaySize = $this->displaySize;
        
        return $returnData;
    }
    
}
