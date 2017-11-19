<?php

namespace App\Classes\Mappers;

use App\Classes\TDG\UserTDG;
use App\Classes\Core\UserCatalog;
use App\Classes\UnitOfWork;
use App\Aspect\IdentityMapAspect;
use Hash;

class UserCatalogMapper {

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
        if ($this->userCatalog->checkUser($email, $password)) {
            return true;
        } else {
            return $this->userTDG->findUserTestPsw($email, $password);
        }
    }

    function getAllCustomers() {
        return $this->userCatalog->getCustomerList();
    }

    function deleteAccount($userID) {
        $this->userCatalog->deleteUser($userID);
    }

}
