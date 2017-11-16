<?php

namespace App\Classes\Mappers;

use App\Classes\TDG\UserTDG;
use App\Classes\Core\UserCatalog;
use App\Classes\UnitOfWork;
use App\Classes\IdentityMap;
use Hash;

class UserCatalogMapper {

    private $userCatalog;
    private $userTDG;
    private $unitOfWork;
    private $identityMap;

    function __construct() {
        $this->userTDG = new UserTDG();
        $this->userCatalog = new UserCatalog($this->userTDG->findAll());
        $this->unitOfWork = new UnitOfWork(['userCatalogMapper' => $this]);
        $this->identityMap = new IdentityMap();
    }

    function saveUser($user) {
        return $this->userTDG->add($user);
    }

    function makeLoginLog($id) {
        date_default_timezone_set('EST');
        $timestamp = date("Y-m-d H:i:s");

        $this->userTDG->insertLoginLog($id, $timestamp);
    }

    function makeNewCustomer($userData) {
        $userData->admin = "0";
        $emailExists = $this->userCatalog->findUser($userData->email);

        if (!$emailExists) {
            $userData->password = Hash::make($userData->password);

            $user = $this->userCatalog->makeCustomer($userData);

            $this->unitOfWork->registerNew($user);
            $this->unitOfWork->commit();

            //Add to identity map
            $this->identityMap->add('User', $user);

            return true;
        } else {
            return false;
        }
    }
    
    function login($email, $password){
        if($this->userCatalog->checkUser($email, $password)){
            return true;
        }else{
            return $this->userTDG->findUserTestPsw($email, $password);
        }
    }

}
