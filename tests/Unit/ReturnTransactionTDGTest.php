<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\TDG\ReturnTransactionTDG;
use App\Classes\Core\ElectronicItem;
use App\Classes\Core\ReturnTransaction;

class ReturnTransactionTDGTest extends TestCase {

    function testInsert() {
        $returnTransactionTDG = new ReturnTransactionTDG();
        $returnTransaction = new ReturnTransaction();
        
        $rTData = new \stdClass();
        $rTData->timestamp = date("Y-m-d H:i:s");
        $rTData->User_id = 4;
        $rTData->ElectronicItem_id = 1;
        $rTData->isComplete = 1;

        $returnTransaction->set($rTData);

        $returnTransactionTDG->insert($returnTransaction);

        $this->assertDatabaseHas('ReturnTransaction', [
            'User_id' => $rTData->User_id,
            'isComplete' => $rTData->isComplete,
            'ElectronicItem_id' => $rTData->ElectronicItem_id,
            'timestamp' => $rTData->timestamp
        ]);
    }

    function testFindAll() {
        $returnTransactionTDG = new ReturnTransactionTDG();
        $returnTransaction1 = new ReturnTransaction();
        $returnTransaction2 = new ReturnTransaction();
        $rTData1 = new \stdClass();
        $rTData2 = new \stdClass();
        
        $rTData1->User_id = 4;
        $rTData1->ElectronicItem_id = 1;
        $rTData1->isComplete = 1;
        $rTData1->timestamp = date("Y-m-d H:i:s");

        $rTData2->User_id = 5;
        $rTData2->ElectronicItem_id = 2;
        $rTData2->isComplete = 1;
        $rTData2->timestamp = date("Y-m-d H:i:s");
        
        $returnTransaction1->set($rTData1);
        $returnTransaction2->set($rTData2);

        $returnTransactionTDG->insert($returnTransaction1);
        $returnTransactionTDG->insert($returnTransaction2);
        $returnTransactionTDG->findAll($returnTransactionTDG);


        $this->assertDatabaseHas('ReturnTransaction', [
            'User_id' => $rTData1->User_id,
            'isComplete' => $rTData1->isComplete,
            'ElectronicItem_id' => $rTData1->ElectronicItem_id,
            'timestamp' => $rTData1->timestamp,
            
            'User_id' => $rTData2->User_id,
            'isComplete' => $rTData2->isComplete,
            'ElectronicItem_id' => $rTData2->ElectronicItem_id,
            'timestamp' => $rTData2->timestamp
        ]);
    }

}
