<?php

namespace App\Classes\Mappers;

use App\Classes\TDG\ElectronicItemTDG;
use App\Classes\TDG\SaleTDG;
use App\Classes\TDG\PaymentTDG;
use App\Classes\TDG\ReturnTransactionTDG;
use App\Classes\Core\SaleCatalog;
use App\Classes\Core\ShoppingCart;
use App\Classes\UnitOfWork;
use App\Classes\Core\ReturnTransaction;
use Session;
use PhpDeal\Annotation as Contract;

class SaleMapper {

    //TDGs
    private $electronicItemTDG;
    private $saleTDG;
    private $paymentTDG;
    private $returnTransactionTDG;
    private $unitOfWork;
    private $saleCatalog;
    private $shoppingCart;

    function __construct($userId) {
        $this->electronicItemTDG = new ElectronicItemTDG();
        $this->saleTDG = new SaleTDG();
        $this->returnTransactionTDG = new ReturnTransactionTDG();
        $this->saleCatalog = new SaleCatalog();
        $this->paymentTDG = new PaymentTDG();

        $this->saleCatalog->setCurrentSale($this->saleTDG->findCurrentSaleFromUser($userId));
        $this->saleCatalog->setSales($this->saleTDG->findAllSales());
        $this->saleCatalog->setReturnTransactions($this->returnTransactionTDG->findAll());

        $this->shoppingCart = ShoppingCart::getInstance();

        $this->shoppingCart->setSLIs($this->electronicItemTDG->findAllShoppingCartSLIFromUser($userId));

        $this->unitOfWork = new UnitOfWork(['saleMapper' => $this]);
    }

    /**
     * Make a new sale for checkout
     *
     * @Contract\Verify("Auth::check() === true && Auth::user()->admin === 0 && $this->shoppingCart->getSize() >= 1")
     *
     */
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

    /**
     * Last step for checkout
     *
     * @return Sale $completedSale
     *
     * @Contract\Ensure("$__result->get()->isComplete === 1 && $__result->get()->payment->get()->amount == $this->shoppingCart->getTotal()")
     */
    function makePayment() {
        $completedSale = $this->saleCatalog->makePayment();

        if ($completedSale) {
            $completedSalePayment = $completedSale->get()->payment;

            $id = $this->paymentTDG->insert($completedSalePayment);
            $completedSalePayment->set((object) ['id' => $id]);

            $completedSale->set((object) ['payment' => $completedSalePayment]);

            $this->saleTDG->update($completedSale);
        }
        return $completedSale;
    }

    function getMyOrders($userId) {
        return $this->saleCatalog->getMyOrders($userId);
    }

    function getMyReturnTransactions($userId) {
        return $this->saleCatalog->getMyReturnTransactions($userId);
    }

    function prepareAddReturn($eIId, $userId) {
        $returnTransaction = new ReturnTransaction();
        $returnTransaction->set((object) ['ElectronicItem_id' => $eIId, 'User_id' => $userId]);

        return $this->unitOfWork->registerNew($returnTransaction);
    }

    /**
     * Last step for checkout
     *
     *
     * //@Contract\Ensure("$eI->getUserId()==null && $eI->get()->Sale_id==null")
     */
    function saveRT($rT) {
        $rT->set((object) ['isComplete' => 1, 'timestamp' => date("Y-m-d H:i:s")]);
        $this->returnTransactionTDG->insert($rT);
        $this->saleCatalog->insertReturnTransaction($rT);

        $eI = $this->saleCatalog->getElectronicItemById($rT->get()->ElectronicItem_id);
        //$eI->set((object) ['Sale_id' => null, 'User_id' => null]);
        $eI->setUserId(null);
        $eI->setSaleId(null);

        $this->electronicItemTDG->update($eI);
        
        //return $eI; //for the contract
    }

    function applyReturns() {
        $this->unitOfWork->commit();
    }

    function cancelReturns() {
        $this->unitOfWork->cancel();
    }

    function setUOWLists($newList, $changedList, $deletedList) {
        $this->unitOfWork->setNewList($newList);
        $this->unitOfWork->setChangedList($changedList);
        $this->unitOfWork->setDeletedList($deletedList);
    }

}
