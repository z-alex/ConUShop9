<?php

namespace App\Classes\Core;

class ComputerSpecification extends ElectronicSpecification {
    private $processorType;
    private $ramSize;
    private $cpuCores;
    private $hdSize;
    private $os;
    
    function __construct($data) {
        parent::__construct($data);
        $this->set($data);
    }
    
    public function set($data) {
        if (isset($data->processorType)) {
            $this->processorType = $data->processorType;
        }
        
        if (isset($data->ramSize)) {
            $this->ramSize = $data->ramSize;
        }
        
        if (isset($data->cpuCores)) {
            $this->cpuCores = $data->cpuCores;
        }
        
        if (isset($data->hdSize)) {
            $this->hdSize = $data->hdSize;
        }
        
        if (isset($data->os)) {
            $this->os = $data->os;
        }
        
        parent::set($data);
    }
    
    public function get() {
        $returnData = parent::get();
        
        $returnData->processorType = $this->processorType;
        $returnData->ramSize = $this->ramSize;
        $returnData->cpuCores = $this->cpuCores;
        $returnData->hdSize = $this->hdSize;
        $returnData->os = $this->os;
        
        return $returnData;
    }
    
}
