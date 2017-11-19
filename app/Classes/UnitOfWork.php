<?php

namespace App\Classes;

use App\Classes\Core\ElectronicSpecification;
use App\Classes\Core\ElectronicItem;
use App\Classes\Core\User;
use Session;

class UnitOfWork {

    private $newList;
    private $changedList;
    private $deletedList;
    private $electronicCatalogMapper;
    private $userCatalogMapper;
    private $shoppingCartMapper;

    function __construct($mappers) {
        $this->newList = array();
        $this->changedList = array();
        $this->deletedList = array();

        if (isset($mappers['electronicCatalogMapper'])) {
            $this->electronicCatalogMapper = $mappers['electronicCatalogMapper'];
        }

        if (isset($mappers['userCatalogMapper'])) {
            $this->userCatalogMapper = $mappers['userCatalogMapper'];
        }

        if (isset($mappers['shoppingCartMapper'])) {
            $this->shoppingCartMapper = $mappers['shoppingCartMapper'];
        }
    }
    
    function setNewList($newList){
        $this->newList = $newList;
    }
    
    function setChangedList($changedList){
        $this->changedList = $changedList;
    }
    
    function setDeletedList($deletedList){
        $this->deletedList = $deletedList;
    }

    function registerNew($object) {
        array_push($this->newList, $object);
        $this->setSession();
    }

    function registerDirty($object) {
        array_push($this->changedList, $object);
        $this->setSession();
    }

    function registerDeleted($object) {
        $exist = false;
        foreach ($this->deletedList as $deleted){
            if($object->get()->id == $deleted->get()->id){
                $exist = true;
            }
        }
        if($exist){
            return false;
        } else {
            array_push($this->deletedList, $object);
            $this->setSession();
            return true;
        }
    }

    function commit() {

        foreach ($this->newList as $new) {
            if ($new instanceof ElectronicSpecification) {
                $this->electronicCatalogMapper->saveES($new);
            }
            if ($new instanceof ElectronicItem) {
                $this->electronicCatalogMapper->saveEI($new);
            }
            if ($new instanceof User) {
                $this->userCatalogMapper->saveUser($new);
            }
        }
        foreach ($this->changedList as $changed) {
            if ($changed instanceof ElectronicSpecification) {
                $this->electronicCatalogMapper->updateES($changed);
            }
            if ($changed instanceof ElectronicItem) {
                $this->shoppingCartMapper->updateEI($changed);
            }
        }
        foreach ($this->deletedList as $deleted) {
            if ($deleted instanceof ElectronicItem) {
                $this->electronicCatalogMapper->deleteEI($deleted);
            }
            
            if ($deleted instanceof ElectronicSpecification) {
                $this->electronicCatalogMapper->deleteES($deleted);
            }
        }


        $this->newList = array();
        $this->changedList = array();
        $this->deletedList = array();
        
        Session::forget('newList');
        Session::forget('changedList');
        Session::forget('deletedList');
    }
    
    function cancel(){
        $this->newList = array();
        $this->changedList = array();
        $this->deletedList = array();

        Session::forget('newList');
        Session::forget('changedList');
        Session::forget('deletedList');
    }
    
    private function setSession(){
        Session::put('newList', $this->newList);
        Session::put('changedList', $this->changedList);
        Session::put('deletedList', $this->deletedList);
    }

}
