<?php

namespace App\Classes\Mappers;

use App\Classes\TDG\UserTDG;
use App\Classes\Core\UserCatalog;
use App\Classes\UnitOfWork;
use App\Aspect\IdentityMapAspect;
use Hash;

class UserMapper {

    private $userCatalog;
    private $userTDG;
    private $unitOfWork;
    private $identityMapAspect;

    function __construct() {
        $this->userTDG = new UserTDG();
        $this->userCatalog = new UserCatalog($this->userTDG->findAll());
        $this->unitOfWork = new UnitOfWork(['userCatalogMapper' => $this]);
        $this->identityMapAspect = new IdentityMapAspect();
    }

    function makeLoginLog($id) {
        date_default_timezone_set('EST');
        $timestamp = date("Y-m-d H:i:s");

        $this->userTDG->insertLoginLog($id, $timestamp);
    }

    function makeNewCustomer($userData) {
        $emailExists = $this->userCatalog->findUser($userData->email);

        if (!$emailExists) {
            $userData->password = Hash::make($userData->password);

            $user = $this->userCatalog->makeCustomer($userData);

            $id = $this->userTDG->add($user);

            $user->set((object) ['id' => $id]);

            //Add for identity map
            $this->identityUser = $user;

            return true;
        } else {
            return false;
        }
    }

    function login($email, $password) {
        // Before the execution of this method, the IdentityMapAspect class verifies if the user is in the IdentityMap. Please check the class in App\Aspect\ for the code.
        
        return $this->userTDG->findUserTestPsw($email, $password);
        
        // Alternative part
        $loggedInUser = $this->userCatalog->checkUser($email, $password);
        
        if ($loggedInUser !== null) {
            $this->userTDG->update($loggedInUser);
            return true;
        }
        
        return false;
    }
    
    function logout(){
        $loggedOutUser = $this->userCatalog->logoutUser();
        
        $this->userTDG->update($loggedOutUser);
    }

    function getAllCustomers() {
        return $this->userCatalog->getCustomerList();
    }

    function getUserInfo($userID) {
        return $this->userCatalog->getUserInfo($userID);
    }

    function deleteAccount($userID) {
        $deletedUser = $this->userCatalog->deleteUser($userID);
        $this->userTDG->update($deletedUser);
    }

}
