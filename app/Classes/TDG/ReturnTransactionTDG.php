<?php

namespace App\Classes\TDG;

class ReturnTransactionTDG {

    private $conn;

    public function __construct() {
        $this->conn = new MySQLConnection();
    }
    
    public function insert($rT){
        $queryString = 'INSERT ReturnTransaction SET ElectronicItem_id = ' . $rT->get()->ElectronicItem_id . ', User_id = ' . $rT->get()->User_id . ', isComplete = ' . $rT->get()->isComplete . ', timestamp = "' . $rT->get()->timestamp.'"';

        $this->conn->directQuery($queryString);
        
        return $this->conn->getPDOConnection()->lastInsertId();
    }
    
    public function findAll(){
        $queryString = 'SELECT * FROM ReturnTransaction';

        return $this->conn->directQuery($queryString);
    }

}
