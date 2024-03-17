<?php

namespace App\Http\Controllers\Api\BankAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccountStoreOrUpdateRequest;
use App\Http\Resources\BankAccountResource;
use App\Services\BankAccountService;

class BankAccountController extends Controller
{
    protected $bankAccountService;
    public function __construct(BankAccountService $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
    }

    public function show($id)
    {
        try {
            $responseValue = $this->bankAccountService->findAllTransactionBankAccount($id);

            $resource = new BankAccountResource($responseValue);
            return $resource->response()->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getAll()
    {
        $responseBankAccounts = $this->bankAccountService->getAllBankAccount();
        return response()->json($responseBankAccounts, 200);
    }

    public function store(BankAccountStoreOrUpdateRequest $request)
    {
        $dataBankAccount = $request->all();

        $bankAccount = $this->bankAccountService->register($dataBankAccount);

        return response()->json($bankAccount, 201);
    }

    public function update(BankAccountStoreOrUpdateRequest $request, $id)
    {
        try {
            $dataBankAccount = $request->all();
            $this->bankAccountService->update($dataBankAccount, $id);

            return response()->noContent();
        } catch (\Exception $e) {
            // Captura a exceção lançada pelo bankAccountService
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $this->bankAccountService->delete($id);

            return response()->json(['message' => 'Bank account deleted successfully'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
