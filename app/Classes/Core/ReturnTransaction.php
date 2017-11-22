<?php

namespace App\Classes\Core;

class ReturnTransaction {
    private $id;
    private $User_id;
    private $ElectronicItem_id;
    private $isComplete;
    private $timestamp;
    
    function __construct(){
        $this->isComplete = 0;
    }
    
    function get(){
        $returnData = new \stdClass();
        
        $returnData->id = $this->id;
        $returnData->User_id = $this->User_id;
        $returnData->ElectronicItem_id = $this->ElectronicItem_id;
        $returnData->isComplete = $this->isComplete;
        $returnData->timestamp = $this->timestamp;
        
        return $returnData;
    }
    
    function set($data){
        if(isset($data->id)){
            $this->id = $data->id;
        }
        
        if(isset($data->User_id)){
            $this->User_id = $data->User_id;
        }
        
        if(isset($data->ElectronicItem_id)){
            $this->ElectronicItem_id = $data->ElectronicItem_id;
        }
        
        if(isset($data->isComplete)){
            $this->isComplete = $data->isComplete;
        }
        
        if(isset($data->timestamp)){
            $this->timestamp = $data->timestamp;
        }
    }
    
}
