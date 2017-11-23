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
        $rTData->id = 1;
        $rTData->User_id = 7;
        $rTData->ElectronicItem_id = 7;
        $rTData->isComplete = 1;

        $returnTransaction->set($rTData);

        $returnTransactionTDG->insert($returnTransaction);

        $this->assertDatabaseHas('ReturnTransaction', [
            'id' => $rTData->id,
            'User_id' => $rTData->User_id,
            'isComplete' => $rTData->isComplete,
            'ElectronicItem_id' => $rTData->ElectronicItem_id,
        ]);
    }

    function testFindAll() {
        $returnTransactionTDG = new ReturnTransactionTDG();
        $returnTransaction1 = new ReturnTransaction();
        $returnTransaction2 = new ReturnTransaction();
        $rTData1 = new \stdClass();
        $rTData2 = new \stdClass();
        $rTData1->id = 1;
        $rTData1->User_id = 7;
        $rTData1->ElectronicItem_id = 7;
        $rTData1->isComplete = 1;

        $rTData2->id = 2;
        $rTData2->User_id = 8;
        $rTData2->ElectronicItem_id = 4;
        $rTData2->isComplete = 1;
        $returnTransaction1->set($rTData1);
        $returnTransaction2->set($rTData2);

        $returnTransactionTDG->findAll($returnTransactionTDG);


        $this->assertDatabaseHas('ReturnTransaction', [
            'id' => $rTData1->id,
            'User_id' => $rTData1->User_id,
            'isComplete' => $rTData1->isComplete,
            'ElectronicItem_id' => $rTData1->ElectronicItem_id,
            'id' => $rTData2->id,
            'User_id' => $rTData2->User_id,
            'isComplete' => $rTData2->isComplete,
            'ElectronicItem_id' => $rTData2->ElectronicItem_id,
        ]);
    }

}
