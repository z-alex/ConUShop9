<?php

namespace App\Classes\Core;

use PhpDeal\Annotation as Contract;
use App\Classes\Core\ElectronicItem;
use App\Classes\Core\SalesLineItem;

/**
 * shopping cart class
 * @Contract\Invariant("$this->size >= 0 && $this->size <= $this->capacity")
 */
class ShoppingCart {

    private static $instance = null;
    private $salesLineItems;

    /**
     * Current size
     *
     * @var int
     */
    private $size;
    private $capacity;

    private function __construct() {
        $this->salesLineItems = array();
        $this->size = 0;
        $this->capacity = 7;
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ShoppingCart();
        }
        return self::$instance;
    }

    /**
     * Adds an item to the shopping cart
     *
     * @param ElectronicItem $eI
     *
     * @Contract\Verify("Auth::check() === true && Auth::user()->admin === 0 && ($this->size <= $this->capacity) && Auth::user()->id === $eI->getUserId() && strtotime($eI->getExpiryForUser()) > strtotime(date('Y-m-d H:i:s')) ")    // missing check if it belongs to this user
     * @Contract\Ensure("$__result === true && ($this->size==$__old->size+1) &&  ($this->size <= $this->capacity)")   //post-condition
     * 
     */
    public function addEIToCart($eI, $eS) {
        $sliFound = null;
        foreach ($this->salesLineItems as $sli) {
            if ($sli->getElectronicSpecification()->get()->id === $eI->get()->ElectronicSpecification_id) {
                $sliFound = $sli;
            }
        }

        if ($sliFound !== null) {
            $sliFound->addElectronicItem($eI);
        } else {
            $sli = new SalesLineItem();

            $sli->setElectronicSpecification($eS);
            $sli->addElectronicItem($eI);

            array_push($this->salesLineItems, $sli);
        }
        
        $eISuccessfullyAdded = false;
        $this->updateSLIs();
        
        foreach($this->salesLineItems as $sli){
            foreach($sli->getElectronicItems() as $eITest){
                if($eITest->get()->id === $eI->get()->id){
                    $eISuccessfullyAdded = true;
                    return $eISuccessfullyAdded;
                }
            }
        }
        
        return $eISuccessfullyAdded;
    }
    
    public function removeFromCart($eIToRemove){
        foreach ($this->salesLineItems as $sli) {
            foreach ($sli->getElectronicItems() as $eI) {
                if ($eIToRemove->get()->id === $eI->get()->id) {
                    $sli->unsetEI($eI);
                }
            }
        }
    }

    /**
     * returns the item that are in shopping cart
     *
     * @Contract\Ensure("$this->size == $__old->size")   //post-condition
     * 
     */
    public function getSalesLineItems() {
        $this->updateSLIs();
        return $this->salesLineItems;
    }

    public function setSLIs($sliDataArray) {
        $this->salesLineItems = array();
        
        foreach ($sliDataArray as $sliData) {
            
            $eI = new ElectronicItem($sliData);

            $sliData->id = $sliData->ElectronicSpecification_id;
            $eS = new ElectronicSpecification($sliData);
            
            $sliFound = null;
            foreach ($this->salesLineItems as $sli) {
                if ($sli->getElectronicSpecification()->get()->id === $eI->get()->ElectronicSpecification_id) {
                    $sliFound = $sli;
                    break;
                }
            }

            if ($sliFound === null) {
                $sli = new SalesLineItem();

                $sli->setElectronicSpecification($eS);
                $sli->addElectronicItem($eI);

                array_push($this->salesLineItems, $sli);
            } else {
                $sliFound->addElectronicItem($eI);
            }
        }
        $this->updateSLIs();
    }
    
    public function updateSLIs() {
        foreach ($this->salesLineItems as $sli) {
            foreach ($sli->getElectronicItems() as $eI) {
                if (strtotime($eI->get()->expiryForUser) === null || $eI->get()->User_id === null) {
                    $sli->unsetEI($eI);
                }
            }
        }
        
        $this->size = $this->getSize();
    }
    
    public function getSize() {
        $count = 0;
        foreach($this->salesLineItems as $sli){
            $count = $count + count($sli->getElectronicItems());
        }
        
        return $count;
    }
    
    public function getTotal() {
        $total = 0;
        
        foreach($this->salesLineItems as $sli){
            $total = $total + $sli->getSubtotal();
        }
        
        return $total;
        
    }

}
