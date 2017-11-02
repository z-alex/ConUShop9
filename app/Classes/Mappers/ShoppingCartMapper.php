<?php



namespace App\Classes\Mappers;

use App\Classes\Core\ElectronicCatalog;
use App\Classes\Core\ShoppingCart;
use App\Classes\TDG\ShoppingCartTDG;
use App\Classes\TDG\ElectronicCatalogTDG;
use App\Classes\UnitOfWork;
use App\Classes\IdentityMap;
use PhpDeal\Annotation as Contract;

class ShoppingCartMapper {

    private $electronicCatalog;
    private $electronicCatalogTDG;
    private $shoppingCart;
    private $shoppingCartTDG;
    private $unitOfWork;
    private $identityMap;

    function __construct($userId) {
        $this->electronicCatalogTDG = new ElectronicCatalogTDG();
        $this->electronicCatalog = new ElectronicCatalog($this->electronicCatalogTDG->findAll());
        $this->shoppingCart = ShoppingCart::getInstance();
        $this->shoppingCartTDG = new ShoppingCartTDG();
        $this->unitOfWork = new UnitOfWork(['shoppingCartMapper' => $this]);
        $this->identityMap = new IdentityMap();

        $this->shoppingCart->setEIList($this->shoppingCartTDG->findAllEIFromUser($userId));
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
        $this->shoppingCartTDG->updateEI($eI);
    }

}