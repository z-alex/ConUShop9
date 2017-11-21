<?php

namespace App\Classes\Core;

use Auth;

class SaleCatalog {

    private $sales;
    private $currentSale;

    function __construct() {
        $this->sales = array();
    }

    function get() {
        $returnData = new \stdClass();

        $returnData->sales = $this->sales;
        $returnData->currentSale = $this->currentSale;

        return $returnData;
    }

    function setCurrentSale($currentSaleData) {
        if ($currentSaleData) {
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

            reset($currentSaleData);
            $firstKey = key($currentSaleData);

            $currentSaleData[$firstKey]->id = $currentSaleData[$firstKey]->Sale_id;
            $sale->set($currentSaleData[$firstKey]);
            $sale->set((object) ['salesLineItemList' => $slis]);

            $this->currentSale = $sale;
        }
    }

    function setSales($data) {
        foreach ($data['salesData'] as $saleData) {
            $sale = new Sale();
            $sale->set($saleData);

            array_push($this->sales, $sale);
        }

        foreach ($this->sales as &$sale) {
            foreach ($data['paymentsData'] as $paymentData) {
                $payment = new Payment();
                $payment->set($paymentData);

                if ($payment->get()->id === $sale->get()->Payment_id) {
                    $sale->set((object) ['payment' => $payment]);
                }
            }

            foreach ($data['eSListData'] as $eSData) {
                foreach ($data['eIListData'] as $eIData) {
                    if ($eIData->Sale_id === $sale->get()->id && $eIData->ElectronicSpecification_id === $eSData->id) {

                        $sliFound = null;
                        foreach ($sale->get()->salesLineItemList as &$sli) {
                            if ($sli->getElectronicSpecification()->get()->id === $eIData->ElectronicSpecification_id) {
                                $sliFound = $sli;
                                break;
                            }
                        }
                        $eI = new ElectronicItem();
                        $eI->set($eIData);
                        if ($sliFound !== null) {
                            $sliFound->addElectronicItem($eI);
                        } else {
                            $sli = new SalesLineItem();
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
                            $sli->setElectronicSpecification($eS);
                            $sli->addElectronicItem($eI);

                            $sale->addSalesLineItem($sli);
                        }
                    }
                }
            }
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
        $deletedSale = $this->currentSale;
        $this->currentSale = null;

        return $deletedSale;
    }

    function makePayment() {
        if ($this->currentSale) { //this if statement is not necessary, it is here to prevent an error when the payment-result page is refreshed
            $this->currentSale->makePayment();
            
            $this->currentSale->becomesComplete();
        }

        return $this->currentSale;
    }

}
