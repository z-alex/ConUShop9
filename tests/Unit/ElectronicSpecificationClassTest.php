<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\Core\ElectronicSpecification;
use App\Classes\Core\ElectronicItem;

class ElectronicSpecificationClassTest extends TestCase {

    /**
     * A test for the get and set methods of the user class
     *
     * @return void
     */
    public function testSetGetTest() {

        $electronic = new ElectronicSpecification();
        $electronicItem = new ElectronicItem();

        $electronicItemData = new \stdClass();
        $electronicItemData->id = 'TESTINGID123';
        $electronicItemData->serialNumber = 'TESTINGSERIAL';
        $electronicItemData->ElectronicSpecification_id = 'TESTAGAIN';

        $electronicItem->set($electronicItemData);
        $electronicItems = array($electronicItem);

        $electronicData = new \stdClass();

        $electronicData->id = '1';
        $electronicData->weight = '100';
        $electronicData->modelNumber = '123';
        $electronicData->brandName = '123';
        $electronicData->price = '123';
        $electronicData->ElectronicType_id = '1';
        $electronicData->electronicItems = $electronicItems;

        $electronic->set($electronicData);
        
        $result = true;

        foreach ($electronicData as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key1 => $value1) {
                    if ($value1->get()->id !== $electronic->get()->$key[$key1]->id &&
                            $value1->get()->serialNumber !== $electronic->get()->$key[$key1]->serialNumber &&
                            $value1->get()->ElectronicSpecification_id !== $electronic->get()->$key[$key1]->ElectronicSpecification_id) {
                        $result = false;
                    }
                }
            } else if ($value !== $electronic->get()->$key) {

                $result = false;
            }
        }

        $this->assertTrue($result);
    }

}
