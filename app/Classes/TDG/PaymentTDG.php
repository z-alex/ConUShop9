<?php

namespace App\Classes\TDG;

use Auth;

class PaymentTDG {

    private $conn;

    public function __construct() {
        $this->conn = new MySQLConnection();
    }
    
    public function insert($payment){
        $queryString = 'INSERT Payment SET amount = ' . $payment->get()->amount;

        $res = $this->conn->directQuery($queryString);
        
        return $this->conn->getPDOConnection()->lastInsertId();
    }

}
