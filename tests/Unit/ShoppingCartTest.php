<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\Core\ElectronicCatalog;
use App\Classes\Core\ElectronicItem;
use App\Classes\Core\ShoppingCart;
use App\Classes\Mappers\ShoppingCartMapper;
use App\Classes\Core\ElectronicSpecification;
use Auth;

class ShoppingCartTest extends TestCase {
    
    /**
     * Test if we can add an item in the ShoppingCart, and if it verifies if the item is in inventory
     */
    public function testAddToShoppingCart(){
        
        //We take the user with the ID 4, which is not an admin
        $this->shoppingCartMapper = new ShoppingCartMapper(4);
        
        $this->shoppingCartMapper->testing = true;
        
        //1 = 1 from DDB, 2 = 2 FROM DDB, 4 = user 4 from DDB
        $result = $this->shoppingCartMapper->addToCart(2, 4, date("Y/m/d H:i:s", strtotime("+5 minutes")));
        
        //Item should be out of stock
        $this->assertTrue($result== 'itemOutOfStock');
    }

}
