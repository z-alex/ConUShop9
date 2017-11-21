<?php

namespace App\Classes\Core;

class ElectronicCatalog {

    private $eSList;

    function __construct() {
        $argv = func_get_args();
        switch (func_num_args()) {
            case 0:
                self::__construct0();
                break;
            case 1:
                self::__construct1($argv[0]);
                break;
        }
    }

    function __construct0() {
        $this->eSList = array();
    }

    function __construct1($eSListData) {
        $this->eSList = array();
        $this->setESList($eSListData);
    }

    function setESList($eSListData) {

        foreach ($eSListData as $eSData) {
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

            array_push($this->eSList, $eS);
        }
    }

    function getESList() {
        $returnObject = array();

        foreach ($this->eSList as $eS) {
            array_push($returnObject, $eS->get());
        }

        return $returnObject;
    }

    function deleteElectronicItem($eI) {
        $id = $eI->get()->id;

        foreach ($this->eSList as $eS) {
            foreach ($eS->getElectronicItems() as $eI) {
                if ($eI->get()->id == $id) {
                    $eS->deleteElectronicItem($id);
                    return $eI;
                }
            }
        }

        return null;
    }
    
    function deleteElectronicSpecification($eSToDelete){
        foreach($this->eSList as $key => $value){
            if($this->eSList[$key]->get()->id === $eSToDelete->get()->id){
                $this->eSList[$key]->set((object) ['isDeleted' => 1]);
            }
        }
    }

    function findElectronicSpecification($modelNumber) {
        $modelNumberExists = false;

        foreach ($this->eSList as $eS) {
            if ($eS->getModelNumber() === $modelNumber) {
                $modelNumberExists = true;
            }
        }

        return $modelNumberExists;
    }

    function getElectronicSpecificationById($id) {
        foreach ($this->eSList as $eS) {
            if ($eS->getId() === $id) {
                return $eS;
            }
        }

        return null;
    }

    function getElectronicItemById($id) {
        foreach ($this->eSList as $eS) {
            foreach($eS->getElectronicItems() as $eI) {
                if ($eI->get()->id === $id) {
                    return $eI;
                }
            }
        }

        return null;
    }
    
    function insertElectronicSpecification($eS) {
        array_push($this->eSList, $eS);

        return $eS;
    }
    
    function insertElectronicItem($eI) {
        foreach($this->eSList as $key => $value){
            if($this->eSList[$key]->get()->id === $eI->get()->ElectronicSpecification_id){
                $this->eSList[$key]->addElectronicItem($eI);
                break;
            }
        }
    }

    function modifyElectronicSpecification($newES) {
        foreach ($this->eSList as &$eS) {
            if ($eS->get()->id === $newES->get()->id) {
                $eS->set($newES->get());
                return $eS;
            }
        }

        return null;
    }

    function reserveFirstEIFromES($eSId, $userId, $expiry) {
        $firstAvailableEI = null;

        foreach ($this->eSList as &$eS) {
            if ($eS->get()->id === $eSId) {
                $firstAvailableEI = $eS->reserveFirstAvailableEI($userId, $expiry);
                break;
            }
        }
        return $firstAvailableEI;
    }

    function unsetUserAndExpiryFromEI($eSId, $userId) {
        $removedEI = null;
        foreach ($this->eSList as $eS) {
            if ($eS->get()->id == $eSId) {
                $removedEI = $eS->unsetUserAndExpiry($userId);
                break;
            }
        }
        return $removedEI;
    }

}
