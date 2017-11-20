<?php

namespace App\Classes\Mappers;

use App\Classes\TDG\ElectronicItemTDG;
use App\Classes\TDG\SaleTDG;
use App\Classes\Core\SaleCatalog;
use App\Classes\Core\ShoppingCart;

class SaleMapper {

    private $saleCatalog;
    private $shoppingCart;
    private $electronicItemTDG;
    private $saleTDG;

    function __construct($userId) {
        $this->electronicItemTDG = new ElectronicItemTDG();
        $this->saleTDG = new SaleTDG();
        $this->saleCatalog = new SaleCatalog();

        $this->saleCatalog->setCurrentSale($this->saleTDG->findCurrentSaleFromUser($userId));

        $this->shoppingCart = ShoppingCart::getInstance();

        $this->shoppingCart->setSLIs($this->electronicItemTDG->findAllShoppingCartSLIFromUser($userId));
    }

    function makeNewSale() {
        $slis = $this->shoppingCart->getSalesLineItems();

        $result = $this->saleCatalog->makeNewSale($slis);
        $sale = $this->saleCatalog->get()->currentSale;

        if ($result) {
            $this->saleTDG->insert($sale);
        }

        return $sale;
    }

    function currentSaleExists() {
        if($this->saleCatalog->get()->currentSale) {
            return true;
        }
        
        return false;
    }
    
    function cancelCheckout(){
        $id = $this->saleCatalog->deleteCurrentSale();
        
        $this->saleTDG->deleteSaleById($id);
    }

}
