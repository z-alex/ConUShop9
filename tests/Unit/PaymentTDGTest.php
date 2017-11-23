<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\TDG\PaymentTDG;
use App\Classes\Core\Payment;

class PaymentTDGTest extends TestCase {

    function testInsert() {
        $paymentTDG = new PaymentTDG();
        $payment = new Payment();
        $paymentData = new \stdClass();
        $paymentData->amount = '1000';

        $payment->set($paymentData);
        $paymentTDG->insert($payment);

        $this->assertDatabaseHas('payment', [
            'amount' => '1000',
        ]);
    }

}
