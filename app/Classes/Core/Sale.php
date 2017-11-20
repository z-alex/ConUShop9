<?php

namespace App\Classes\Core;

class Sale {
    private $id;
    private $User_id;
    private $isComplete;
    private $timestamp;
    private $salesLineItemList;
    
    function __construct() {
        $this->salesLineItemList = array();
        $this->isComplete = 0;
    }
    
    function set($saleData){
        if(isset($saleData->id)){
            $this->id = $saleData->id;
        }
        
        if(isset($saleData->User_id)){
            $this->User_id = $saleData->User_id;
        }
        
        if(isset($saleData->isComplete)){
            $this->isComplete = $saleData->isComplete;
        }
        
        if(isset($saleData->timestamp)){
            $this->timestamp = $saleData->timestamp;
        }
        
        if(isset($saleData->salesLineItemList)){
            $this->salesLineItemList = $saleData->salesLineItemList;
        }
    }
    
    function get(){
        $returnData = new \stdClass();
        
        $returnData->id = $this->id;
        $returnData->User_id = $this->User_id;
        $returnData->isComplete = $this->isComplete;
        $returnData->timestamp = $this->timestamp;
        $returnData->salesLineItemList = $this->salesLineItemList;
        
        return $returnData;
    }
    
}