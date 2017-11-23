<?php

namespace Tests\Unit;

use App\Classes\TDG\ElectronicItemTDG;
use App\Classes\Core\ElectronicItem;

class ElectronicItemTDGTest {

    /**
     * Test the insertion of an electronic item in the DDB
     */
    public function testInsertEI() {
        $electronicItemTDG = new ElectronicItemTDG;
        $eS = new ElectronicSpecification();

        $eSData = new \stdClass();
        $eSData->id = 2;
        $eSData->dimension = '100 x 200 x 300';
        $eSData->weight = 400;
        $eSData->modelNumber = 'ABC123DEF5D';
        $eSData->brandName = 'LG';
        $eSData->hdSize = '500';
        $eSData->price = '1000';
        $eSData->processorType = 'AMD';
        $eSData->ramSize = '16';
        $eSData->cpuCores = '4';
        $eSData->batteryInfo = '12 hours';
        $eSData->os = 'Windows';
        $eSData->camera = 1;
        $eSData->touchScreen = 1;
        $eSData->displaySize = 10;
        $eSData->ElectronicType_id = 3;

        $eS->set($eSData);

        $electronicItemTDG->insert($eS);

        $eIData = new \stdClass();
        $eIData->id = 1;
        $eIData->serialNumber = "ABC123";
        $eIData->ElectronicSpecification_id = 2;

        $electronicItemTDG->insert($eSData->modelNumber, $eIData);

        $this->assertDatabaseHas('ElectronicItem', [
            'id' => 1,
            'serialNumber' => 'ABC123',
        ]);
    }

    /**
     * Test the deletion of an Electronic Item from the DDB
     */
    public function testDeleteEI() {
        $electronicItemTDG = new ElectronicItemTDG;

        $eIData = new \stdClass();
        $eIData->id = 1;
        $eIData->serialNumber = "ABC123";
        $eIData->ElectronicSpecification_id = 2;
        $eI = new ElectronicItem($eIData);

        $electronicItemTDG->delete($eI);

        $this->assertDatabaseMissing('ElectronicItem', [
            'id' => 1,
            'serialNumber' => 'ABC123',
        ]);
    }

}
