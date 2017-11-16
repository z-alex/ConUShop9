<?php

namespace App\Classes\TDG;

class ElectronicSpecificationTDG {
    private $conn;
    
    public function __construct() {
        $this->conn = new MySQLConnection();
    }
    
    public function insert($electronicSpecification) {
        $parameters = $this->unsetUselessESProperties($electronicSpecification);
        unset($parameters->id);

        $queryString = 'INSERT INTO ElectronicSpecification SET ';

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
    
    public function update($electronicSpecification) {
        $parameters = $this->unsetUselessESProperties($electronicSpecification);

        $queryString = 'UPDATE ElectronicSpecification SET ';

        foreach ($parameters as $key => $value) {
            if ($value !== null && $key != 'id' ) {
                $queryString .= $key . ' = :' . $key;
                $queryString .= ' , ';
            }
        }

        //We delete the last useless ' , '
        $queryString = substr($queryString, 0, -2);

        $queryString .= ' WHERE id = :id';
        return $this->conn->query($queryString, $parameters);
    }
    
    public function find($parameters) {
        $queryString = 'SELECT *
            FROM ElectronicSpecification
            WHERE ';

        //For each key, (ex: id, email, etc.), we build the query
        foreach ($parameters as $key => $value) {
            $queryString .= $key . ' = :' . $key;
            $queryString .= ' AND ';
        }
        //We delete the last useless ' AND '
        $queryString = substr($queryString, 0, -5);
        //We send to MySQLConnection the associative array, to bind values to keys
        //Please mind that stdClass and associative arrays are not the same data structure, althought being both based on the big family of hashtables
        return $this->conn->query($queryString, $parameters);
    }
    
    public function findAll() {
        //$queryString = 'SELECT * FROM ElectronicSpecification JOIN ElectronicType ON ElectronicType.id = ElectronicSpecification.ElectronicType_id JOIN ElectronicItem ON ElectronicSpecification.id = ElectronicItem.ElectronicSpecification_id';
        $queryString = 'SELECT * FROM ElectronicSpecification';
        $eSDataList = $this->conn->directQuery($queryString);
        //dd($eSDataList);
        foreach ($eSDataList as &$eSData) {
            //dd($eSData);
            $queryString = 'SELECT *
            FROM ElectronicItem
            WHERE ';
            $parameters = array('ElectronicSpecification_id' => $eSData->id);
            //For each key, (ex: id, email, etc.), we build the query
            foreach ($parameters as $key => $value) {
                $queryString .= $key . ' = :' . $key;
                $queryString .= ' AND ';
            }
            //We delete the last useless ' AND '
            $queryString = substr($queryString, 0, -5);
            //We send to MySQLConnection the associative array, to bind values to keys
            //Please mind that stdClass and associative arrays are not the same data structure, althought being both based on the big family of hashtables
            $eIDataList = $this->conn->query($queryString, $parameters);
            //dd($eIDataList);
            $eSData->electronicItems = $eIDataList;
        }
        return $eSDataList;
        //dd($eSDataList);
    }

    private function unsetUselessESProperties($object){
        $objectData = (array) $object->get();
        foreach ($objectData as $key => $value) {
            if (is_array($objectData[$key]) || is_null($objectData[$key])) {
                unset($objectData[$key]);
            }
        }
        //unset($electronicSpecificationData['id']);
        unset($objectData['ElectronicType_name']);
        unset($objectData['ElectronicType_name']);
        unset($objectData['ElectronicType_displaySizeUnit']);
        $parameters = (object) $objectData;

        return $parameters;
    }
    
}
