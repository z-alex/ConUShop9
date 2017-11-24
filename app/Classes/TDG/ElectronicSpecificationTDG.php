<?php

namespace App\Classes\TDG;

class ElectronicSpecificationTDG {

    private $conn;

    public function __construct() {
        $this->conn = new MySQLConnection();
    }

    public function lock($eS) {
        $queryString = 'SELECT * FROM ElectronicSpecification WHERE id = ' . $eS->get()->id . ' AND isLocked = 0';

        $queryResult = $this->conn->directQuery($queryString);

        if (count($queryResult) >= 1 && $queryResult[0] != null) {
            $queryString = 'UPDATE ElectronicSpecification SET isLocked = 1 WHERE id = ' . $eS->get()->id;

            $this->conn->directQuery($queryString);

            return true;
        } else {
            return false;
        }
    }

    public function unlock($eS) {
        $queryString = 'UPDATE ElectronicSpecification SET isLocked = 0 WHERE id = ' . $eS->get()->id;

        $this->conn->directQuery($queryString);
    }

    public function insert($eS) {
        $parameters = $this->unsetUselessESProperties($eS);
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
        $this->conn->query($queryString, $parameters);

        return $this->conn->getPDOConnection()->lastInsertId();
    }

    public function update($eS) {
        $parameters = $this->unsetUselessESProperties($eS);

        $queryString = 'UPDATE ElectronicSpecification SET ';

        foreach ($parameters as $key => $value) {
            if ($value !== null && $key != 'id') {
                $queryString .= $key . ' = :' . $key;
                $queryString .= ' , ';
            }
        }

        //We delete the last useless ' , '
        $queryString = substr($queryString, 0, -2);

        $queryString .= ' WHERE id = :id';
        return $this->conn->query($queryString, $parameters);
    }

    public function delete($eS) {
        $queryString = 'DELETE FROM ElectronicItem WHERE ';
        $queryString .= 'ElectronicSpecification_id' . ' = :' . 'ElectronicSpecification_id';

        $parameters = new \stdClass();
        $parameters->ElectronicSpecification_id = $eS->get()->id;

        $this->conn->query($queryString, $parameters);

        $queryString = 'UPDATE ElectronicSpecification SET isDeleted = 1 WHERE ';
        $queryString .= 'id' . ' = :' . 'id';

        $parameters = new \stdClass();
        $parameters->id = $eS->get()->id;

        $this->conn->query($queryString, $parameters);
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

        foreach ($eSDataList as &$eSData) {

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

            $eSData->electronicItems = $eIDataList;
        }
        return $eSDataList;
    }

    private function unsetUselessESProperties($object) {
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
