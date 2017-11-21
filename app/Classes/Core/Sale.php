<?php

namespace App\Classes\Core;

class Sale {
    private $id;
    private $User_id;
    private $isComplete;
    private $timestamp;
    private $Payment_id;
    private $salesLineItemList;
    private $payment;
    
    function __construct() {
        $this->isComplete = 0;
        $this->salesLineItemList = array();
    }
    
    function addSalesLineItem($sli){
        array_push($this->salesLineItemList, $sli);
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
        
        if(isset($saleData->Payment_id)){
            $this->Payment_id = $saleData->Payment_id;
        }
        
        if(isset($saleData->salesLineItemList)){
            $this->salesLineItemList = $saleData->salesLineItemList;
        }
        
        if(isset($saleData->payment)){
            $this->payment = $saleData->payment;
        }
        
    }
    
    function get(){
        $returnData = new \stdClass();
        
        $returnData->id = $this->id;
        $returnData->User_id = $this->User_id;
        $returnData->isComplete = $this->isComplete;
        $returnData->timestamp = $this->timestamp;
        $returnData->Payment_id = $this->Payment_id;
        $returnData->salesLineItemList = $this->salesLineItemList;
        $returnData->payment = $this->payment;
        
        return $returnData;
    }
    
    public function getTotal() {
        $total = 0;
        
        foreach($this->salesLineItemList as $sli){
            $total = $total + $sli->getSubtotal();
        }
        
        return $total;
        
    }
    
    public function makePayment() {
        $this->payment = new Payment();
        $this->payment->set((object) ['amount' => $this->getTotal()]);
    }
    
    public function becomesComplete() {
        $this->isComplete = 1;
        $this->timestamp = date("Y/m/d H:i:s");
        
        foreach($this->salesLineItemList as $sli){
            $eIList = $sli->getElectronicItems();
            foreach($eIList as &$eI){
                $eI->setExpiryForUser(null);
            }
            $sli->set((object) ['electronicItems' => $eIList]);
        }
    }
    
}