<?php

namespace App\Classes\TDG;

class ElectronicItemTDG {
    private $conn;
    
    public function __construct() {
        $this->conn = new MySQLConnection();
    }
    
    public function insert($eI) {
        $queryString = 'SELECT * FROM ElectronicSpecification';
        $electronicSpecifications = $this->conn->directQuery($queryString);
        $electronicSpecificationId = -1;
        foreach ($electronicSpecifications as $electronicSpecification) {
            if ($electronicSpecification->id === $eI->get()->ElectronicSpecification_id) {
                $electronicSpecificationId = $electronicSpecification->id;
            }
        }
        
        $parameters = new \stdClass();
        $parameters->serialNumber = $eI->get()->serialNumber;
        $parameters->ElectronicSpecification_id = $electronicSpecificationId;
        
        $queryString = 'INSERT INTO ElectronicItem SET ';
        foreach ($parameters as $key => $value) {
            if ($value !== null) {
                $queryString .= $key . ' = :' . $key;
                $queryString .= ' , ';
            }
        }
        
        //We delete the last useless ' , '
        $queryString = substr($queryString, 0, -2);
        $this->conn->query($queryString, $parameters);
        
        return $this->conn->getPDOConnection()->lastInsertId();
    }
    
    function update($eI) {
        $queryString = "UPDATE ElectronicItem SET User_id = " . $eI->get()->User_id . ", expiryForUser= '" . $eI->get()->expiryForUser . "' WHERE id= " . $eI->get()->id;

        return $this->conn->directQuery($queryString);
    }
    
    public function delete($electronicItem) {
        $queryString = 'DELETE FROM ElectronicItem WHERE ';
        $queryString .= 'id' . ' = :' . 'id';

        $parameters = new \stdClass();
        $parameters->id = $electronicItem->get()->id;
        return $this->conn->query($queryString, $parameters);
    }
    
    function findAllEIFromUser($userId){
        $queryString = "SELECT ElectronicItem.id, serialNumber, ElectronicSpecification_id, User_id, expiryForUser FROM ElectronicItem  JOIN User ON ElectronicItem.User_id = User.id WHERE User.id = " . $userId;
        $eIsData = $this->conn->directQuery($queryString);
        
        foreach($eIsData as $key => $value){
            if(strtotime($eIsData[$key]->expiryForUser) < strtotime(date("Y-m-d H:i:s"))){
                unset($eIsData[$key]);
            }
        }
        
        //dd($eIsData);
        
        return $eIsData;
    }
    
    function findAllSLIFromUser($userId){
        $queryString = "SELECT ElectronicItem.id, ElectronicSpecification_id, serialNumber, User_id, expiryForUser, dimension, weight, "
                . "modelNumber, brandName, hdSize, price, processorType, ramSize, cpuCores, batteryInfo, os, camera, touchScreen, ElectronicType_id, "
                . "displaySize, image "
                . "FROM ElectronicItem  JOIN ElectronicSpecification ON ElectronicItem.ElectronicSpecification_id = ElectronicSpecification.id WHERE User_id = " . $userId;
        $eIsData = $this->conn->directQuery($queryString);
        
        foreach($eIsData as $key => $value){
            if(strtotime($eIsData[$key]->expiryForUser) < strtotime(date("Y-m-d H:i:s"))){
                unset($eIsData[$key]);
            }
        }
        
        //dd($eIsData);
        
        return $eIsData;
    }
    
}
