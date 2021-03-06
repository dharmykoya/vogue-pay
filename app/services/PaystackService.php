<?php


namespace App\services;

/**
 *
 */
class PaystackService
{
    private $baseUrl;
    private $header;

    public function __construct()
    {
        $this->authenticate();
    }

    public function authenticate()
    {
        $env = config("app.env");

        if ($env == "production") {
            $env = 'live';
        }

        if ($env == "test") {
            $env = "test";
        }

        $this->baseUrl = "https://api.paystack.co";
        $secretKey = config("api.paystack.secret_key.$env");
        $this->header = [
            'Authorization' => "Bearer $secretKey",
            'Content-Type' => 'application/json',
        ];

        return 'Authorization: Bearer ' . config("api.paystack.secret_key.$env");
    }
}
