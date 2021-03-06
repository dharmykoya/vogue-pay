<?php


namespace Tests;


trait MockHttp
{
    public function startMocking()
    {
        Http::fake([
            'api.paystack.co/bank/resolve?account_number=000011123&bank_code=058' => Http::response('{
              "status": true,
              "message": "Account number resolved",
              "data": {
                 "account_number": "000011123",
                 "account_name": "TEST USER",
                 "bank_id": 29
              }
            }')
        ]);
    }
}
