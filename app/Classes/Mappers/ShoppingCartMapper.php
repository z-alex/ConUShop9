<?php

namespace App\Classes\Mappers;

use App\Classes\Core\ElectronicCatalog;
use App\Classes\Core\ShoppingCart;
use App\Classes\TDG\ElectronicSpecificationTDG;
use App\Classes\TDG\ElectronicItemTDG;
use PhpDeal\Annotation as Contract;

class ShoppingCartMapper {

    private $electronicCatalog;
    private $electronicSpecificationTDG;
    private $electronicItemTDG;
    private $shoppingCart;
    public $testing; //Using by the testing script to bypass the contract related to authentication

    function __construct($userId) {
        $this->electronicSpecificationTDG = new ElectronicSpecificationTDG;
        $this->electronicItemTDG = new ElectronicItemTDG();
        $this->electronicCatalog = new ElectronicCatalog($this->electronicSpecificationTDG->findAll());
        $this->shoppingCart = ShoppingCart::getInstance();

        $this->shoppingCart->setSLIs($this->electronicItemTDG->findAllShoppingCartSLIFromUser($userId));
    }

    /**
     * @Contract\Verify("($this->testing ||(Auth::check() == true && Auth::user()->admin == 0))&& count($this->shoppingCart->getSalesLineItems()) < 7")
     */
    function addToCart($eSId, $userId, $expiry) {
        if ($this->shoppingCart->getSize() < 7) {
            $eI = $this->electronicCatalog->reserveFirstEIFromES($eSId, $userId, $expiry);

            if ($eI != null) {
                $eS = $this->electronicCatalog->getElectronicSpecificationById($eSId);
                
                $this->shoppingCart->addEIToCart($eI, $eS);
                $this->electronicItemTDG->update($eI);
                
                return 'itemAddedToCart';
            } else {
                return 'itemOutOfStock';
            }
        } else {
            return 'shoppingCartFull';
        }
    }

    function viewCart(){
        return $this->shoppingCart;
    }

    function removeFromCart($eSId, $userId){
        $removedEI = $this->electronicCatalog->unsetUserAndExpiryFromEI($eSId, $userId);
        $this->shoppingCart->removeFromCart($removedEI);
        $this->electronicItemTDG->update($removedEI);
        return 'Item Removed';
    }

}
