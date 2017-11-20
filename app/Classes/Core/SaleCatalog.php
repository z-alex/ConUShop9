<?php

namespace App\Classes\Core;

use Auth;

class SaleCatalog {

    private $sales;
    private $currentSale;

    function __construct() {
        $this->sales = array();
    }

    function get(){
        $returnData = new \stdClass();
        
        $returnData->sales = $this->sales;
        $returnData->currentSale = $this->currentSale;
        
        return $returnData;
    }

    function setCurrentSale($currentSaleData) {
        if($currentSaleData) {
            $eIList = array();
            foreach ($currentSaleData as $eIData) {
                $eIData->id = $eIData->ElectronicItem_id;
                $eI = new ElectronicItem($eIData);
                array_push($eIList, $eI);
            }

            $eSList = array();
            foreach ($currentSaleData as $eSData) {
                $eSData->id = $eSData->ElectronicSpecification_id;

                switch ($eSData->ElectronicType_id) {
                    case "1":
                        $eS = new DesktopSpecification($eSData);
                        break;
                    case "2":
                        $eS = new LaptopSpecification($eSData);
                        break;
                    case "3":
                        $eS = new MonitorSpecification($eSData);
                        break;
                    case "4":
                        $eS = new TabletSpecification($eSData);
                        break;
                }
                array_push($eSList, $eS);
            }
            $eSList = array_map("unserialize", array_unique(array_map("serialize", $eSList)));

            $slis = array();
            foreach ($eSList as $eS) {
                $salesLineItem = new SalesLineItem();
                $salesLineItem->set((object) ['electronicSpecification' => $eS]);
                foreach ($eIList as $eI) {
                    if ($eI->get()->ElectronicSpecification_id == $eS->get()->id) {
                        $salesLineItem->addElectronicItem($eI);
                    }
                }
                array_push($slis, $salesLineItem);
            }

            $sale = new Sale();
            $currentSaleData[0]->id = $currentSaleData[0]->Sale_id;
            $sale->set($currentSaleData[0]);
            $sale->set((object) ['salesLineItemList' => $slis]);

            $this->currentSale = $sale;
        }
    }

    function makeNewSale($slis) {
        if (!$this->currentSale) {
            $sale = new Sale();
            $sale->set((object) ['salesLineItemList' => $slis, 'User_id' => Auth::user()->id]);

            $this->currentSale = $sale;
            
            return true;
        }

        return false;
    }
    
    function deleteCurrentSale() {
        $id = $this->currentSale->get()->id;
        $this->currentSale = null;
        
        return $id;
    }

}
