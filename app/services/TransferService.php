<?php


namespace App\services;

use App\Models\Transfer;
use App\Models\TransferRecipient;

/**
 *
 */
class TransferService
{

    protected $paystackService;


    public function __construct(PaystackService $paystackService) {
        $this->paystackService = $paystackService;
    }

    public function getTransferRecipient($accountNumber, $userId)
    {
        return TransferRecipient::where(['account_number' => $accountNumber, 'user_id' => $userId])->first();
    }

    public function getTransferRecipientById($recipientId)
    {
        return TransferRecipient::find($recipientId)->first();
    }

    public function verifyAccountNumber($data)
    {
        $response = $this->paystackService->verifyAccountNumber($data['account_number'], $data['bank_code']);

        if (!$response['response']['status']) {
            return ['status' => false, 'message' => 'verification failed'];
        }

        return [
            'status' => true,
            'data' => $response['response']['data'],
            'message' => $response['response']['message'],
        ];
}

    public function createRecipientService($recipientData)
    {
        $user = auth()->user();
        $recipient = $this->getTransferRecipient($recipientData['account_number'], $user['id']);

        if($recipient) {
            return [
                'status' => true,
                'message' =>  'Recipient fetched',
                'data' => $recipient
            ];
        }

        $response = $this->paystackService->createRecipientService(
            $recipientData['name'],
            $recipientData['account_number'],
            $recipientData['bank_code']
        );

        $response = $response['response'];
        if(!$response['status']) {
            return [
                'status' => false,
                'message' =>  $response['message'],
            ];
        }
        $newRecipient = $user->transferRecipients()->create([
            "recipient_code" => $response['data']['recipient_code'],
            "account_number" => $response['data']['details']['account_number'],
            "account_name" => $response['data']['name'],
            "bank_code" => $response['data']['details']['bank_code'],
            "bank_name" => $response['data']['details']['bank_name']
        ]);

        if (!$newRecipient) {
            return [
                'status' => false,
                'message' =>  'Recipient not created'
            ];
        }

        return [
            'status' => true,
            'message' =>  'Recipient created',
            'data' => $newRecipient
        ];
    }

    public function initiateTransferService($transferData)
    {
        $user = auth()->user();
        $response = $this->paystackService->initiateTransfer(
            $transferData['recipient_code'],
            $$transferData['amount'] * 100,
            $transferData['reason']
        );

        $response = $response['response'];
        if(!$response['status']) {
            return [
                'status' => false,
                'message' =>  $response['message'],
            ];
        }
        $newTransfer = $user->transfers()->create([
            "reference" => $response['data']['reference'],
            "amount" => $response['data']['amount'],
            "source" => $response['data']['source'],
            "transfer_code" => $response['data']['transfer_code'],
            "reason" => $response['data']['reason'],
            "paystack_id" => $response['data']['id'],
            "currency" => $response['data']['currency'],
            "integration" => $response['data']['integration'],
            "status" => Transfer::TRANSFER_PENDING,
        ]);

        if (!$newTransfer) {
            return [
                'status' => false,
                'message' =>  'Recipient not created'
            ];
        }

        return [
            'status' => true,
            'message' =>  'Recipient created',
            'data' => $newTransfer
        ];
    }
}
