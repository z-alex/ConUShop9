<?php

namespace App\Classes\Core;

class Payment {
    private $id;
    private $amount;
    
    function __construct(){
        $amount = 0;
    }
    
    function get(){
        $returnData = new \stdClass();
        
        $returnData->id = $this->id;
        $returnData->amount = $this->amount;
        
        return $returnData;
    }
    
    function set($paymentData){
        if(isset($paymentData->id)){
            $this->id = $paymentData->id;
        }
        if(isset($paymentData->amount)){
            $this->amount = $paymentData->amount;
        }
    }
    
}
