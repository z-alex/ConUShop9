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

    public function update($sale) {
        $parameters = $this->unsetUselessProperties($sale);

        $queryString = 'UPDATE Sale SET ';

        foreach ($parameters as $key => $value) {
            if ($value !== null) {
                $queryString .= $key . ' = :' . $key;
                $queryString .= ' , ';
            }
        }

        //We delete the last useless ' , '
        $queryString = substr($queryString, 0, -2);

        $queryString .= ' WHERE id = ' . $sale->get()->id;

        $this->conn->query($queryString, $parameters);



        //set Payment_id
        $queryString = 'UPDATE Sale SET Payment_id = ' . $sale->get()->payment->get()->id . ' WHERE id = ' . $sale->get()->id;
        $this->conn->directQuery($queryString);



        $queryString = 'UPDATE ElectronicItem SET expiryForUser = NULL WHERE Sale_id = ' . $sale->get()->id;
        $this->conn->directQuery($queryString);
    }

    function delete($sale) {
        $queryString = 'UPDATE ElectronicItem SET Sale_id = NULL WHERE Sale_id = ' . $sale->get()->id;

        $eIList = $this->conn->directQuery($queryString);

        $queryString = 'DELETE FROM Sale WHERE id = ' . $sale->get()->id;

        $this->conn->directQuery($queryString);
    }

    function findCurrentSaleFromUser($userId) {
        //$queryString = "SELECT ElectronicItem.id as ElectronicItem_id, Sale.id as Sale_id, ElectronicItem.ElectronicSpecification_id as ElectronicSpecification_id, serialNumber, expiryForUser, isComplete, timestamp FROM ElectronicItem  "
        $queryString = "SELECT "
                . "ElectronicItem.id as ElectronicItem_id, ElectronicSpecification_id, serialNumber, expiryForUser, "
                . "dimension, weight, modelNumber, brandName, hdSize, price, processorType, ramSize, cpuCores, batteryInfo, os, camera, touchScreen, ElectronicType_id, displaySize, image, "
                . "Sale.id as Sale_id,  isComplete, timestamp, Sale.User_id as User_id "
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

    function findAllSales() {
        $queryString = "SELECT "
                . "ElectronicItem.id, ElectronicSpecification_id, serialNumber, ElectronicItem.User_id, expiryForUser, ElectronicItem.Sale_id "
                . "FROM ElectronicItem  "
                . "JOIN Sale ON ElectronicItem.Sale_id = Sale.id "
                . "JOIN ElectronicSpecification ON ElectronicItem.ElectronicSpecification_id = ElectronicSpecification.id "
                . "JOIN Payment ON Sale.Payment_id = Payment.id";

        $eIListData = $this->conn->directQuery($queryString);
        $eIListData = array_map("unserialize", array_unique(array_map("serialize", $eIListData)));

        $queryString = "SELECT "
                . "ElectronicSpecification.id, dimension, weight, modelNumber, brandName, hdSize, price, processorType, ramSize, cpuCores, batteryInfo, os, camera, touchScreen, ElectronicType_id, displaySize, image "
                . "FROM ElectronicItem  "
                . "JOIN Sale ON ElectronicItem.Sale_id = Sale.id "
                . "JOIN ElectronicSpecification ON ElectronicItem.ElectronicSpecification_id = ElectronicSpecification.id "
                . "JOIN Payment ON Sale.Payment_id = Payment.id";

        $eSListData = $this->conn->directQuery($queryString);
        $eSListData = array_map("unserialize", array_unique(array_map("serialize", $eSListData)));

        $queryString = "SELECT "
                . "Sale.id,  isComplete, timestamp, Sale.User_id, Payment_id "
                . "FROM ElectronicItem  "
                . "JOIN Sale ON ElectronicItem.Sale_id = Sale.id "
                . "JOIN ElectronicSpecification ON ElectronicItem.ElectronicSpecification_id = ElectronicSpecification.id "
                . "JOIN Payment ON Sale.Payment_id = Payment.id";

        $salesData = $this->conn->directQuery($queryString);
        $salesData = array_map("unserialize", array_unique(array_map("serialize", $salesData)));
        
        $queryString = "SELECT "
                . "Payment.id,  amount "
                . "FROM ElectronicItem  "
                . "JOIN Sale ON ElectronicItem.Sale_id = Sale.id "
                . "JOIN ElectronicSpecification ON ElectronicItem.ElectronicSpecification_id = ElectronicSpecification.id "
                . "JOIN Payment ON Sale.Payment_id = Payment.id";

        $paymentsData = $this->conn->directQuery($queryString);
        $paymentsData = array_map("unserialize", array_unique(array_map("serialize", $paymentsData)));

        $data = ['eIListData' => $eIListData, 'eSListData' => $eSListData, 'salesData' => $salesData, 'paymentsData' => $paymentsData];

        return $data;
    }

    private function unsetUselessProperties($sale) {
        $objectData = (array) $sale->get();

        unset($objectData['payment']);

        foreach ($objectData as $key => $value) {
            if (is_array($objectData[$key]) || is_null($objectData[$key])) {
                unset($objectData[$key]);
            }
        }

        $parameters = (object) $objectData;

        return $parameters;
    }

}
