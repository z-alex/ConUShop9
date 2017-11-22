<?php

namespace App\Classes\Core;

use Hash;

class UserCatalog
{

    private $userList;

    function __construct()
    {
        $this->userList = array();
        $argv = func_get_args();
        switch (func_num_args()) {
            case 1:
                self::__construct1($argv[0]);
                break;
        }
    }

    function __construct1($userListData)
    {
        $this->userList = array();

        $this->setUserList($userListData);
    }

    function setUserList($userListData)
    {
        //dd($userListData);
        foreach ($userListData as $userData) {
            $user = new User($userData);
            array_push($this->userList, $user);
        }
    }

    function getUserList()
    {
        $users = array();

        foreach ($this->userList as $user) {
            array_push($users, $user->get());
        }

        return $users;
    }

    function checkUser($email, $password)
    {
        //IdentityMapAspect checks before this method if it's in the map. Please see the method login in IdentityMapAspect
        
        foreach ($this->userList as $user) {
            if ($user->get()->email === $email) {
                if (Hash::check($user->get()->password, $password)) {
				$this->loginUser($userID);
                    return true;
                }

                break;
            }
        }

        return false;
    }

    function findUser($email)
    {
        $emailExists = false;
        foreach ($this->userList as $user) {
            if ($user->get()->email === $email) {
                $emailExists = true;
                break;
            }
        }
        return $emailExists;
    }

    function makeCustomer($userData)
    {

        $user = new User($userData);

        array_push($this->userList, $user);
        return $user;
    }

	function getCustomerList(){
		
		$customers = array();
		foreach($this->getUserlist() as $user){
			if(!$user->admin && !$user->isDeleted){
				array_push($customers, $user);
			}
		}
		return $customers;
	}


	function getUserInfo($userID) {
		foreach($this->userList as $user) {
			if ($user->get()->id == $userID){
				return $user;
			 }
		 }
		 // Return null to denote that the user was not found.
		  return null;
	}

	function deleteUser($userID) {
		$data = new \stdClass();
		$data->isDeleted = 1;
		$data->isLoggedIn = 0;

		foreach($this->userList as $user) {
			if ($user->get()->id == $userID){
				$user->set($data);
				break;
			 }
		 }
	}

	function loginUser($userID){
		$data = new \stdClass();
		$data->isLoggedIn = 1;
		$data->isDeleted = 0;
		
		foreach($this->userList as $user) {
			if ($user->get()->id == $userID){
				$user->set($data);
				break;
			 }
		 }

	}
}
