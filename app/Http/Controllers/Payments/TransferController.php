<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreateRecipientRequest;
use App\Http\Requests\Payment\VerifyAccountNumberRequest;
use App\Http\Resources\TransferRecipientResource;
use App\services\TransferService;
use Symfony\Component\HttpFoundation\Response;

class TransferController extends Controller
{
    protected $transferService;


    public function __construct(TransferService $transferService) {
        $this->transferService = $transferService;
    }

    public function resolveAccountNumber(VerifyAccountNumberRequest $request)
    {
        try {
            $response = $this->transferService->verifyAccountNumber($request->validated());

            if (!$response['status']) {
               return $this->errorResponse(null, 'verification failed');
            }

            return $this->successResponse(
                $response['data'],
                $response['message'],
                Response::HTTP_OK
            );
        } catch (\Exception $error) {
            $this->fatalErrorResponse($error);
        }
    }

    public function createRecipient(CreateRecipientRequest $request)
    {
        try {
            $response = $this->transferService->createRecipientService($request->validated());

            if (!$response['status']) {
                return $this->errorResponse(null, $response['message']);
            }

            return $this->successResponse(
                new TransferRecipientResource($response['data']),
                $response['message'],
                Response::HTTP_CREATED
            );
        } catch (\Exception $error) {
            $this->fatalErrorResponse($error);
        }
    }
}
