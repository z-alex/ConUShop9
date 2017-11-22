<?php

namespace App\Classes\TDG;

use Hash;

class UserTDG {

    private $conn;

    public function __construct() {
        $this->conn = new MySQLConnection();
    }

    /**
     *
     * @param type $parameters "Associative array with the SQL field and the wanted value. Ex: $parameter['id'] = 4; $parameter['email'] = 'admin@admin.com';
     */
    public function find($parameters) {

        $queryString = 'SELECT id, firstname, lastName, email, phone, admin,
                physicalAddress, password FROM User
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

        $queryString = 'SELECT * FROM User';

        $userDataList = $this->conn->directQuery($queryString);

        return $userDataList;
    }

    public function insertLoginLog($userId, $timestamp) {
        $parameters = new \stdClass();
        $parameters->User_id = $userId;
        $parameters->timestamp = $timestamp;

        $queryString = 'INSERT INTO LoginLog SET ';

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

    public function findUserTestPsw($email, $password) {
        $parameters = new \stdClass();
        $parameters->email = $email;

        $queryString = 'SELECT * FROM User WHERE ';

        //For each key, (ex: id, email, etc.), we build the query
        foreach ($parameters as $key => $value) {

            $queryString .= $key . ' = :' . $key;
            $queryString .= ' AND ';
        }
        //We delete the last useless ' AND '
        $queryString = substr($queryString, 0, -5);

        //We send to MySQLConnection the associative array, to bind values to keys
        //Please mind that stdClass and associative arrays are not the same data structure, althought being both based on the big family of hashtables
        $array = $this->conn->query($queryString, $parameters);

        if (count($array) > 0 && Hash::check($password, $array[0]->password)) {
            return true;
        } else {
            return false;
        }
    }

    public function add($user) {

        $objectData = (array) ( $user->get());

        foreach ($objectData as $key => $value) {
            if (is_array($objectData[$key]) || is_null($objectData[$key])) {
                unset($objectData[$key]);
            }
        }

        $parameters = (object) $objectData;

        $queryString = 'INSERT INTO User SET ';


        foreach ((array) $parameters as $key => $value) {
            $queryString .= $key . ' = :' . $key;
            $queryString .= ' , ';
        }

        //We delete the last useless ' , '
        $queryString = substr($queryString, 0, -2);


        $this->conn->query($queryString, $parameters);
        
        return $this->conn->getPDOConnection()->lastInsertId();
    }

	public function deleteAccount($userID){
		 $queryString = "UPDATE user SET isDeleted = 1, isLoggedIn=0 WHERE id =" . $userID ;


        return $this->conn->directQuery($queryString);
	}

	public function loginUser($userID){
		 $queryString = "UPDATE user SET isLoggedIn = 1, isDeleted = 0 WHERE id =" . $userID ;

        return $this->conn->directQuery($queryString);
	}


}
