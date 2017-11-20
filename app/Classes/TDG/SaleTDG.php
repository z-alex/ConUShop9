<?php

namespace App\Classes\TDG;

use Auth;

class SaleTDG {

    private $conn;

    public function __construct() {
        $this->conn = new MySQLConnection();
    }

    public function insert($sale) {
        $queryString = 'INSERT INTO Sale SET ';

        $parameters = $this->unsetUselessProperties($sale);

        foreach ($parameters as $key => $value) {
            if ($value !== null) {
                $queryString .= $key . ' = :' . $key;
                $queryString .= ' , ';
            }
        }

        //We delete the last useless ' , '
        $queryString = substr($queryString, 0, -2);
        $this->conn->query($queryString, $parameters);

        $Sale_id = $this->conn->getPDOConnection()->lastInsertId();





        //dd($sale->get()->salesLineItemList);

        foreach ($sale->get()->salesLineItemList as $sli) {
            foreach ($sli->getElectronicItems() as $eI) {
                $queryString = 'UPDATE ElectronicItem SET Sale_id = ' . $Sale_id . ' WHERE id = ' . $eI->get()->id;

                $this->conn->directQuery($queryString);
            }
        }
    }

    function findCurrentSaleFromUser($userId) {
        //$queryString = "SELECT ElectronicItem.id as ElectronicItem_id, Sale.id as Sale_id, ElectronicItem.ElectronicSpecification_id as ElectronicSpecification_id, serialNumber, expiryForUser, isComplete, timestamp FROM ElectronicItem  "
        $queryString = "SELECT ElectronicItem.id as ElectronicItem_id, ElectronicSpecification_id, serialNumber, expiryForUser, dimension, weight, "
                . "modelNumber, brandName, hdSize, price, processorType, ramSize, cpuCores, batteryInfo, os, camera, touchScreen, ElectronicType_id, "
                . "displaySize, image, Sale.id as Sale_id,  isComplete, timestamp, Sale.User_id as User_id "
                . "FROM ElectronicItem  "
                . "JOIN Sale ON ElectronicItem.Sale_id = Sale.id "
                . "JOIN ElectronicSpecification ON ElectronicItem.ElectronicSpecification_id = ElectronicSpecification.id "
                . "WHERE Sale.User_id = " . $userId;
        $saleData = $this->conn->directQuery($queryString);

        foreach ($saleData as $key => $value) {
            if ($saleData[$key]->isComplete) {
                unset($saleData[$key]);
            }
        }

        return $saleData;
    }

    function deleteSaleById($id) {
        $queryString = 'UPDATE ElectronicItem SET Sale_id = NULL WHERE Sale_id = ' . $id;

        $eIList = $this->conn->directQuery($queryString);
        
        $queryString = 'DELETE FROM Sale WHERE id = ' . $id;

        $this->conn->directQuery($queryString);
    }

    private function unsetUselessProperties($sale) {
        $objectData = (array) $sale->get();
        foreach ($objectData as $key => $value) {
            if (is_array($objectData[$key]) || is_null($objectData[$key])) {
                unset($objectData[$key]);
            }
        }

        $parameters = (object) $objectData;

        return $parameters;
    }

}
