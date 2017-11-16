<?php

namespace App\Classes\TDG;

class ElectronicItemTDG {
    private $conn;
    
    public function __construct() {
        $this->conn = new MySQLConnection();
    }
    
    public function insert($modelNumber, $parameters) {
        $queryString = 'SELECT * FROM ElectronicSpecification';
        $electronicSpecifications = $this->conn->directQuery($queryString);
        $electronicSpecificationId = -1;
        foreach ($electronicSpecifications as $electronicSpecification) {
            if ($electronicSpecification->modelNumber === $modelNumber) {
                $electronicSpecificationId = $electronicSpecification->id;
            }
        }
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
        return $this->conn->query($queryString, $parameters);
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
        /*
         * SELECT left_tbl.*
  FROM left_tbl LEFT JOIN right_tbl ON left_tbl.id = right_tbl.id
  WHERE right_tbl.id IS NULL;
         */
        //dd(Auth::check());
        $queryString = "SELECT ElectronicItem.id, serialNumber, ElectronicSpecification_id, User_id, expiryForUser FROM ElectronicItem  JOIN User ON ElectronicItem.User_id = User.id WHERE User.id = " . $userId;
        $eIsData = $this->conn->directQuery($queryString);
        
        foreach($eIsData as $key => $value){
            if(strtotime($eIsData[$key]->expiryForUser) < strtotime(date("Y-m-d H:i:s"))){
                unset($eIsData[$key]);
            }
        }
        
        return $eIsData;
    }
    
}
