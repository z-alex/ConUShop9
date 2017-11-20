<?php

namespace App\Classes\Core;

class SalesLineItem {

    private $electronicSpecification;
    private $electronicItems;

    function __construct() {
        $this->electronicItems = array();
    }

    public function set($sliData) {
        if (isset($sliData->electronicSpecification)) {
            $this->electronicSpecification = $sliData->electronicSpecification;
        }
        if (isset($sliData->electronicItems)) {
            $this->electronicItems = $sliData->electronicItems;
        }
    }

    public function setElectronicSpecification($eS) {
        $this->electronicSpecification = $eS;
    }

    public function addElectronicItem($eI) {
        array_push($this->electronicItems, $eI);
    }

    public function getElectronicSpecification() {
        return $this->electronicSpecification;
    }

    public function getElectronicItems() {
        return $this->electronicItems;
    }

    public function unsetEI($eI) {
        foreach ($this->electronicItems as $key => $value) {
            if ($eI->get()->id === $this->electronicItems[$key]->get()->id) {
                unset($this->electronicItems[$key]);
            }
        }
    }

}
