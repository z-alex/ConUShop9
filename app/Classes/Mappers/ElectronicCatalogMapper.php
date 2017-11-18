<?php

namespace App\Classes\Mappers;

use App\Classes\TDG\ElectronicSpecificationTDG;
use App\Classes\TDG\ElectronicItemTDG;
use App\Classes\Core\ElectronicCatalog;
use App\Classes\Core\ElectronicItem;
use App\Classes\UnitOfWork;
use App\Classes\IdentityMap;
use App\Classes\Core\DesktopSpecification;
use App\Classes\Core\LaptopSpecification;
use App\Classes\Core\MonitorSpecification;
use App\Classes\Core\TabletSpecification;

class ElectronicCatalogMapper {

    private $electronicCatalog;
    private $electronicSpecificationTDG;
    private $unitOfWork;
    private $identityMap;
    private $lockFilePointer;

    function __construct() {
        $this->electronicSpecificationTDG = new ElectronicSpecificationTDG();
        $this->electronicItemTDG = new ElectronicItemTDG();
        $this->electronicCatalog = new ElectronicCatalog($this->electronicSpecificationTDG->findAll());
        $this->unitOfWork = new UnitOfWork(['electronicCatalogMapper' => $this]);
        $this->identityMap = new IdentityMap();
    }

    function saveES($electronicSpecification) {
        $this->electronicSpecificationTDG->insert($electronicSpecification);
        
        $this->electronicCatalog->insertElectronicSpecification($electronicSpecification);
    }

    function saveEI($eI) {
        $this->electronicItemTDG->insert($eI);

        $this->electronicCatalog->insertElectronicItem($eI);
    }

    function updateES($electronicSpecification) {
        $this->electronicSpecificationTDG->update($electronicSpecification);

        $this->electronicCatalog->modifyElectronicSpecification($electronicSpecification);
    }

    function deleteEI($electronicItem) {
        $this->electronicItemTDG->delete($electronicItem);

        $this->identityMap->delete('ElectronicItem', $electronicItem);

        $this->electronicCatalog->deleteElectronicItem($electronicItem);
    }

    function deleteES($eS) {
        $this->electronicSpecificationTDG->delete($eS);

        $this->identityMap->delete('ElectronicSpecification', $eS);

        $this->electronicCatalog->deleteElectronicSpecification($eS);
    }

    function applyChanges() {
        $this->unitOfWork->commit();
    }

    function cancelChanges() {
        $this->unitOfWork->cancel();
    }

    function makeNewElectronicSpecification($eSData) {
        $modelNumberExists = $this->electronicCatalog->findElectronicSpecification($eSData->modelNumber);

        if (!$modelNumberExists) {
            //Add to eSList of the catalog
            switch ($eSData->ElectronicType_name) {
                case "Desktop":
                    $eS = new DesktopSpecification($eSData);
                    break;
                case "Laptop":
                    $eS = new LaptopSpecification($eSData);
                    break;
                case "Monitor":
                    $eS = new MonitorSpecification($eSData);
                    break;
                case "Tablet":
                    $eS = new TabletSpecification($eSData);
                    break;
            }
            
            $this->unitOfWork->registerNew($eS);

            return true;
        } else {
            return false;
        }
    }

    function prepareModifyES($quantity, $eSData) {
        $newModelNumberExists = $this->electronicCatalog->findElectronicSpecification($eSData->modelNumber);

        if (!$newModelNumberExists || $this->electronicCatalog->getElectronicSpecificationById($eSData->id)->get()->modelNumber === $eSData->modelNumber) {
            $eS = $this->electronicCatalog->getElectronicSpecificationById($eSData->id);

            $eS->set($eSData);

            $this->unitOfWork->registerDirty($eS);

            if (sizeOf($eS->get()->electronicItems) === 0) {
                $serialNumber = $this->generateSerialNumber();
                for ($i = 1; $i <= $quantity; $i++) {
                    $electronicItemData = new \stdClass();
                    $electronicItemData->serialNumber = $serialNumber . $i;
                    $electronicItemData->ElectronicSpecification_id = $eS->get()->id;

                    $eI = new ElectronicItem($electronicItemData);

                    $this->unitOfWork->registerNew($eI);
                }
            } else {
                for ($i = 1; $i <= $quantity; $i++) {
                    $lastChar = substr(end($eS->get()->electronicItems)->serialNumber, -1);
                    $serialNumber = substr(end($eS->get()->electronicItems)->serialNumber, 0, -1);

                    $electronicItemData = new \stdClass();
                    $electronicItemData->serialNumber = $serialNumber . ($lastChar + $i);
                    $electronicItemData->ElectronicSpecification_id = $eS->get()->id;

                    $eI = new ElectronicItem($electronicItemData);

                    $this->unitOfWork->registerNew($eI);
                }
            }

            return true;
        } else {
            return false;
        }
    }

    function prepareDeleteEI($eIId) {
        $eI = $this->electronicCatalog->getElectronicItemById($eIId);

        $this->unitOfWork->registerDeleted($eI);
    }

    function prepareDeleteES($eSId) {
        $eS = $this->electronicCatalog->getElectronicSpecificationById($eSId);

        $this->unitOfWork->registerDeleted($eS);
    }

    function getAllElectronicSpecifications() {
        $this->lockDataAccess();

        $electronicSpecifications = $this->electronicCatalog->getESList();

        $this->unlockDataAccess();

        return $electronicSpecifications;
    }

    function getElectronicSpecification($id) {
        $this->lockDataAccess();

        $electronicSpecification = $this->electronicCatalog->getElectronicSpecificationById($id)->get();

        $this->unlockDataAccess();

        return $electronicSpecification;
    }

    private function generateSerialNumber() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $serialNumber = '';

        for ($i = 0; $i < 12; $i++) {
            $serialNumber .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $serialNumber;
    }

    private function lockDataAccess() {
        $this->lockFilePointer = fopen(app_path('Locks/dataAccess'), 'c'); //set file pointer
        flock($this->lockFilePointer, LOCK_EX); //lock file
    }

    private function checkLock() {
        flock($this->lockFilePointer, LOCK_NB);
    }

    private function unlockDataAccess() {
        flock($this->lockFilePointer, LOCK_UN); //unlock file
        fclose($this->lockFilePointer); //close file
    }

    function getESFilteredAndSortedByCriteria($eSType, $criteriaArray, $sortBy) {
        // parameter is an array of criterion to be applied to the initial array: "$array"

        $eSArray = $this->electronicCatalog->getESList();

        // Filter By Electronic Type
        if (!is_null($eSType)) {
            foreach ($eSArray as $key => $value) {
                if ($eSArray[$key]->ElectronicType_name !== $eSType) {
                    unset($eSArray[$key]);
                }
            }
        }

        // Filter By Price Range
        $filteredByPrice = array();
        $containsPriceRange = false;

        foreach ($criteriaArray as $key => $value) {
            if (strpos($key, 'priceRange') !== false) {
                $containsPriceRange = true;
            }
        }

        if ($containsPriceRange) {
            foreach ($criteriaArray as $key => $value) { // filter out
                if (strpos($key, 'priceRange') !== false) { //if the criteria contains a "-" then it's a criteria
                    $price = explode("-", $value);

                    foreach ($eSArray as $eS) {
                        if ($eS->price >= $price[0] && $eS->price < $price[1]) {
                            array_push($filteredByPrice, $eS);
                        }
                    }
                }
            }
        } else {
            $filteredByPrice = $eSArray;
        }

        // Filter by Brand Name
        $filteredByBrandName = array();
        $containsBrandName = false;

        foreach ($criteriaArray as $key => $value) {
            if (strpos($key, 'brandName') !== false) {
                $containsBrandName = true;
            }
        }

        if ($containsBrandName) {
            foreach ($criteriaArray as $key => $value) { // filter out
                if (strpos($key, 'brandName') !== false) { //if the criteria contains a "-" then it's a criteria
                    foreach ($filteredByPrice as $eS) {
                        if ($eS->brandName === $value) {
                            array_push($filteredByBrandName, $eS);
                        }
                    }
                }
            }
        } else {
            $filteredByBrandName = $filteredByPrice;
        }

        // Filter by Display Size
        $filteredByDisplaySize = array();
        $containsDisplaySize = false;

        foreach ($criteriaArray as $key => $value) {
            if (strpos($key, 'displaySize') !== false) {
                $containsDisplaySize = true;
            }
        }

        if ($containsDisplaySize) {
            foreach ($criteriaArray as $key => $value) { // filter out
                if (strpos($key, 'displaySize') !== false) { //if the criteria contains a "-" then it's a criteria
                    foreach ($filteredByBrandName as $eS) {
                        if (isset($eS->displaySize) && $eS->displaySize === $value) {
                            array_push($filteredByDisplaySize, $eS);
                        }
                    }
                }
            }
        } else {
            $filteredByDisplaySize = $filteredByBrandName;
        }

        // Filter by TouchScreen
        $filteredByTouchScreen = array();
        $containsTouchScreen = false;

        foreach ($criteriaArray as $key => $value) {
            if (strpos($key, 'touchScreen') !== false) {
                $containsTouchScreen = true;
            }
        }

        if ($containsTouchScreen) {
            foreach ($criteriaArray as $key => $value) { // filter out
                if (strpos($key, 'touchScreen') !== false) { //if the criteria contains a "-" then it's a criteria
                    foreach ($filteredByDisplaySize as $eS) {
                        if (isset($eS->touchScreen) && $eS->touchScreen === $value) {
                            array_push($filteredByTouchScreen, $eS);
                        }
                    }
                }
            }
        } else {
            $filteredByTouchScreen = $filteredByDisplaySize;
        }

        $filteredDone = $filteredByTouchScreen;

        // Sort By
        if (!is_null($sortBy)) {
            if ($sortBy === "priceAscending") {
                usort($filteredDone, function($a, $b) {
                    return $a->price <=> $b->price;
                });
            } else {
                if ($sortBy === "priceDescending") {
                    usort($filteredDone, function($a, $b) {
                        return $b->price <=> $a->price;
                    });
                }
            }
        }

        return $filteredDone;
    }

    function getESByType($eSType) {

        $eSArray = $this->electronicCatalog->getESList();

        if (!is_null($eSType)) {
            foreach ($eSArray as $key => $value) {
                if ($eSArray[$key]->ElectronicType_name !== $eSType) {
                    unset($eSArray[$key]);
                }
            }
        }

        return $eSArray;
    }

    function setUOWLists($newList, $changedList, $deletedList) {
        $this->unitOfWork->setNewList($newList);
        $this->unitOfWork->setChangedList($changedList);
        $this->unitOfWork->setDeletedList($deletedList);
    }

}
