<?php



namespace App\Classes\Mappers;

use App\Classes\Core\ElectronicCatalog;
use App\Classes\Core\ShoppingCart;
use App\Classes\TDG\ElectronicSpecificationTDG;
use App\Classes\TDG\ElectronicItemTDG;
use App\Classes\UnitOfWork;
use App\Classes\IdentityMap;

class ShoppingCartMapper {

    private $electronicCatalog;
    private $electronicSpecificationTDG;
    private $electronicItemTDG;
    private $shoppingCart;
    private $unitOfWork;
    private $identityMap;

    function __construct($userId) {
        $this->electronicSpecificationTDG = new ElectronicSpecificationTDG;
        $this->electronicItemTDG = new ElectronicItemTDG();
        $this->electronicCatalog = new ElectronicCatalog($this->electronicSpecificationTDG->findAll());
        $this->shoppingCart = ShoppingCart::getInstance();
        $this->unitOfWork = new UnitOfWork(['shoppingCartMapper' => $this]);
        $this->identityMap = new IdentityMap();

        $this->shoppingCart->setEIList($this->electronicItemTDG->findAllEIFromUser($userId));
    }

    /**
     * //@Contract\Verify("Auth::check() && Auth::user()->admin === 0 && count($this->shoppingCart->getEIList()) < 7")
     */
    function addToCart($eSId, $userId, $expiry) {
        if (count($this->shoppingCart->getEIList()) < 7) {
            $eI = $this->electronicCatalog->reserveFirstEIFromES($eSId, $userId, $expiry);

            if ($eI != null) {
                $this->shoppingCart->addEIToCart($eI);
                $this->unitOfWork->registerDirty($eI);
                $this->unitOfWork->commit();
                
                return 'itemAddedToCart';
            } else {
                return 'itemOutOfStock';
            }
        } else {
            return 'shoppingCartFull';
        }
    }

    function updateEI($eI) {
        $this->electronicItemTDG->update($eI);
    }

    function viewCart(){
        return $this->electronicCatalog->getESListFromEIList($this->shoppingCart->getEIList());
    }

    function removeFromCart($eSId, $userId){
        $removedEI = $this->electronicCatalog->unsetUserAndExpiryFromEI($eSId, $userId);
        $this->unitOfWork->registerDirty($removedEI);
        $this->unitOfWork->commit();
        $this->shoppingCart->updateEIList();
        return 'Item Removed';
    }

}
