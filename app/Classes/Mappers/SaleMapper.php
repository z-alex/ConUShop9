<?php

namespace App\Classes\Mappers;

use App\Classes\TDG\ElectronicItemTDG;
use App\Classes\TDG\SaleTDG;
use App\Classes\TDG\PaymentTDG;
use App\Classes\Core\SaleCatalog;
use App\Classes\Core\ShoppingCart;

class SaleMapper {
    //TDGs
    private $electronicItemTDG;
    private $saleTDG;
    private $paymentTDG;
    
    private $saleCatalog;
    private $shoppingCart;

    function __construct($userId) {
        $this->electronicItemTDG = new ElectronicItemTDG();
        $this->saleTDG = new SaleTDG();
        $this->saleCatalog = new SaleCatalog();
        $this->paymentTDG = new PaymentTDG();

        $this->saleCatalog->setCurrentSale($this->saleTDG->findCurrentSaleFromUser($userId));
        $this->saleCatalog->setSales($this->saleTDG->findAllSales());

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
        if ($this->saleCatalog->get()->currentSale) {
            return true;
        }

        return false;
    }

    function cancelCheckout() {
        $sale = $this->saleCatalog->deleteCurrentSale();

        $this->saleTDG->delete($sale);
    }

    function makePayment() {
        $completedSale = $this->saleCatalog->makePayment();

        if($completedSale) {
            $completedSalePayment = $completedSale->get()->payment;

            $id = $this->paymentTDG->insert($completedSalePayment);
            $completedSalePayment->set((object) ['id' => $id]);

            $completedSale->set((object) ['payment' => $completedSalePayment]);

            $this->saleTDG->update($completedSale);
        }
        return $completedSale;
    }
    
    function getMyOrders($userId){
        return $this->saleCatalog->getMyOrders($userId);
    }

}
