<?php

namespace App\Classes\Core;

use Hash;
use Auth;

class UserCatalog {

    private $userList;

    function __construct() {
        $this->userList = array();
        $argv = func_get_args();
        switch (func_num_args()) {
            case 1:
                self::__construct1($argv[0]);
                break;
        }
    }

    function __construct1($userListData) {
        $this->userList = array();

        $this->setUserList($userListData);
    }

    function setUserList($userListData) {
        foreach ($userListData as $userData) {
            $user = new User($userData);
            array_push($this->userList, $user);
        }
    }

    function getUserList() {
        return $this->userList;
    }

    function checkUser($email, $password) {
        //IdentityMapAspect checks before this method if it's in the map. Please see the method login in IdentityMapAspect

        foreach ($this->userList as &$user) {
            if ($user->get()->email === $email && $user->get()->isLoggedIn == 0 && $user->get()->isDeleted == 0 && Hash::check($password, $user->get()->password)) {
                $user->becomesLoggedIn();
                return $user;
            }
        }

        return null;
    }

    function logoutUser() {
        $user = new User(Auth::user());
        $user->set((object) ['isLoggedIn' => 0]);
        
        return $user;
    }

    function findUser($email) {
        $emailExists = false;
        foreach ($this->userList as $user) {
            if ($user->get()->email === $email) {
                $emailExists = true;
                break;
            }
        }
        return $emailExists;
    }

    function makeCustomer($userData) {
        $user = new User($userData);

        array_push($this->userList, $user);
        return $user;
    }

    function getCustomerList() {

        $customers = array();
        foreach ($this->getUserlist() as $user) {
            if (!$user->get()->admin) {
                array_push($customers, $user);
            }
        }
        return $customers;
    }

    function getUserInfo($userID) {
        foreach ($this->userList as $user) {
            if ($user->get()->id == $userID) {
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

        foreach ($this->userList as $user) {
            if ($user->get()->id == $userID) {
                $user->set($data);
                return $user;
            }
        }

        return null;
    }

}
