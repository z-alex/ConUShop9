<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\Core\Payment;


class PaymentClassTest extends TestCase {

	function testSetGet(){
		# Step 1: Set up needed variables for a situation to test.
		$payment = new Payment();
		$paymentData = new \stdClass();
		$paymentData->id = '1';
		$paymentData->amount = '1000';

		$payment->set($paymentData); 
		$retrievedPaymentData = $payment->get();

		$this->assertTrue($retrievedPaymentData->id == $paymentData->id, $retrievedPaymentData->amount == $paymentData->amount);
		
	}
}
