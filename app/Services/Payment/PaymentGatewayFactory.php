<?php

namespace App\Services\Payment;

class PaymentGatewayFactory
{
    public static function make(): PaymentGatewayInterface
    {
        return match (config('payment.default')) {
            // 'wave' => new WaveGateway(),
            default => new SimulatedPaymentGateway(),
        };
    }
}
