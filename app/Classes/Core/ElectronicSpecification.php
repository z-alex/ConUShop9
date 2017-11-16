<?php

namespace App\Classes\Core;

class ElectronicSpecification {

    private $id;
    private $weight;
    private $modelNumber;
    private $brandName;
    private $image;
    private $electronicItems;
    private $price;
    private $ElectronicType_id;
    private $ElectronicType_name;
    private $ElectronicType_dimensionUnit;
    private $ElectronicType_displaySizeUnit;

    function __construct($data) {
        $this->electronicItems = array();
        $this->set($data);
    }

    public function addElectronicItem($electronicItemData) {
        $electronicItem = new ElectronicItem($electronicItemData);

        array_push($this->electronicItems, $electronicItem);

        return $electronicItem;
    }

    public function deleteElectronicItem($id) {
        foreach ($this->electronicItems as $key => $value) {
            if ($id === $this->electronicItems[$key]->getId()) {
                unset($this->electronicItems[$key]);
            }
        }
    }

    public function set($data) {
        if (isset($data->id)) {
            $this->id = $data->id;
        }
        if (isset($data->weight)) {
            $this->weight = $data->weight;
        }
        if (isset($data->modelNumber)) {
            $this->modelNumber = $data->modelNumber;
        }
        if (isset($data->brandName)) {
            $this->brandName = $data->brandName;
        }
        if (isset($data->price)) {
            $this->price = $data->price;
        }
        if (isset($data->image)) {
            $this->image = $data->image;
        }

        if (isset($data->electronicItems)) {
            //must delete current list of items, otherwise
            //the modifyElectronicSpecification will add the existing items again
            //even if you haven't modified their values
            foreach ($this->electronicItems as $electronicItemIndex => $singleItem) {
                $this->deleteElectronicItem($this->electronicItems[$electronicItemIndex]->getId());
            }
            foreach ($data->electronicItems as $key => $value) {
                $this->addElectronicItem($data->electronicItems[$key]);
            }
        }

        if (isset($data->ElectronicType_id)) {
            $this->ElectronicType_id = $data->ElectronicType_id;
            switch ($data->ElectronicType_id) {
                case 1:
                    $this->ElectronicType_name = 'Desktop';
                    $this->ElectronicType_displaySizeUnit = 'inch';
                    break;
                case 2:
                    $this->ElectronicType_name = 'Laptop';
                    $this->ElectronicType_displaySizeUnit = 'inch';
                    break;
                case 3:
                    $this->ElectronicType_name = 'Monitor';
                    $this->ElectronicType_displaySizeUnit = 'inch';
                    break;
                case 4:
                    $this->ElectronicType_name = 'Tablet';
                    $this->ElectronicType_displaySizeUnit = 'inch';
                    break;
            }
        }
    }

    public function get() {
        $returnData = new \stdClass();

        $returnData->id = $this->id;
        $returnData->weight = $this->weight;
        $returnData->modelNumber = $this->modelNumber;
        $returnData->brandName = $this->brandName;
        $returnData->price = $this->price;
        $returnData->ElectronicType_id = $this->ElectronicType_id;
        $returnData->ElectronicType_name = $this->ElectronicType_name;
        $returnData->ElectronicType_dimensionUnit = $this->ElectronicType_dimensionUnit;
        $returnData->ElectronicType_displaySizeUnit = $this->ElectronicType_displaySizeUnit;
        $returnData->image = $this->image;

        $electronicItemsData = array();
        foreach ($this->electronicItems as $electronicItem) {
            array_push($electronicItemsData, $electronicItem->get());
        }

        $returnData->electronicItems = $electronicItemsData;

        return $returnData;
    }

    public function getElectronicItems() {
        return $this->electronicItems;
    }

    public function getModelNumber() {
        return $this->modelNumber;
    }

    public function getId() {
        return $this->id;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getImage() {
        return $this->image;
    }

    public function reserveFirstAvailableEI($userId, $expiry) {
        $eI = $this->findNextAvailableEI();

        if ($eI != null) {
            $eI->setUserId($userId);
            $eI->setExpiryForUser($expiry);
        }


        return $eI;
    }

    /**
     * Helper function to find next available EI of the electronic specification
     * 
     * return type
     */
    private function &findNextAvailableEI() {
        foreach ($this->electronicItems as &$eI) {
            if (($eI->getExpiryForUser() === null) || ($eI->getUserId() === null) || (strtotime($eI->getExpiryForUser()) < strtotime(date("Y-m-d H:i:s")))) {
                return $eI;
            }
        }

        $result = null;

        return $result;
    }

    function unsetUserAndExpiry($userId) {
        $eIToRemove = null;

        foreach ($this->electronicItems as $eI) {
            if ($eI->getUserId() == $userId) {
                $eIToRemove = $eI;
                break;
            }
        }

        $eIToRemove->setUserId("null");
        $eIToRemove->setExpiryForUser(null);
        return $eIToRemove;
    }

}
