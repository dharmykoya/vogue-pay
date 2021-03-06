<?php

namespace Tests\Unit\Paystack;

use App\services\PaystackService;
use PHPUnit\Framework\TestCase;
use Mockery\Mock;

class ResolveAccountNumberTest extends TestCase
{
    public static function mockPaystackResolveAccount()
    {
       return app()->instance(PaystackService::class, \Mockery::mock(PaystackService::class, function ($mock) {
            /** @var Mock $mock */
            $mock->shouldReceive('verifyAccountNumber')->andReturn([
                "status" => true,
                "message" => "Account number resolved",
                "data" => [
                    "account_number" => "000011123",
                    "account_name" => "TEST USER",
                    "bank_id" => 29
                ]
            ]);
        }));
    }
}
