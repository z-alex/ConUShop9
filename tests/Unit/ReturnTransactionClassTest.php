<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\Core\ReturnTransaction;


class ReturnTransactionClassTest extends TestCase {
	
	function testSetGet(){
		$returnTransaction = new ReturnTransaction();
		$returnData = new \stdClass();
		$returnData->id = 1;
		$returnData->User_id = 7;
		$returnData->ElectronicItem_id = 7;
		$returnData->isComplete = 1;

		$returnTransaction->set($returnData); 
		$retrievedRTData = $returnTransaction->get();

		$this->assertTrue($retrievedRTData->id == $returnData->id,
		$retrievedRTData->User_id == $returnData->User_id,
		$retrievedRTData->ElectronicItem_id == $returnData->ElectronicItem_id,
		$retrievedRTData->isComplete == $returnData->isComplete
		);
	}


	
}