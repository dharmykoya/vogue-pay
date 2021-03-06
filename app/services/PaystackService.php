<?php


namespace App\services;

use Illuminate\Support\Facades\Http;

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

        if ($env == "test" || $env == "local") {
            $env = "test";
        }

        $this->baseUrl = "https://api.paystack.co";
        $secretKey = config("api.paystack.secret_key_$env");
        $this->header = [
            'Authorization' => "Bearer $secretKey",
            'Content-Type' => 'application/json',
        ];

        return 'Authorization: Bearer ' . config("api.paystack.secret_key.$env");
    }

    public function verifyAccountNumber($accountNumber, $bankCode)
    {
        $response = Http::withHeaders($this->header)
            ->get("$this->baseUrl/bank/resolve?account_number=$accountNumber&bank_code=$bankCode");

        if ($response->failed()) {
            return ['status' => false, 'response' => $response->json(), 'message' => 'Account verification failed.'];
        }

        return ['status' => 'success', 'response' => $response->json()];
    }

    public function createRecipientService($name, $accountNumber, $bankCode, $type="nuban", $currency = "NGN")
    {
        $response = Http::withHeaders($this->header)
            ->post("$this->baseUrl/transferrecipient", [
                "name" => $name,
                "account_number" => $accountNumber,
	            "bank_code" => $bankCode,
                "currency" => $currency
            ]);

        if ($response->failed()) {
            return ['status' => false, 'response' => $response->json(), 'message' => 'Account verification failed.'];
        }

        return ['status' => true, 'response' => $response->json()];
    }

    public function initiateTransfer($recipient, $amount, $reason, $source = "balance")
    {
        $response = Http::withHeaders($this->header)
            ->post("$this->baseUrl/transfer", [
                "recipient" => $recipient,
                "amount" => $amount,
	            "reason" => $reason,
                "source" => $source
            ]);

        if ($response->failed()) {
            return ['status' => false, 'response' => $response->json(), 'message' => 'Account verification failed.'];
        }

        return ['status' => true, 'response' => $response->json()];
    }
}
