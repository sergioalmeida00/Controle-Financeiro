<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionsStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function show(Request $request)
    {

        try {
            $filters = $request->all();
            $responseTransactions = $this->transactionService->findAllTransactions($filters);
            return response()->json($responseTransactions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(TransactionsStoreRequest $request)
    {
        try {
            $dataTransaction = $request->all();

            $transaction = $this->transactionService->register($dataTransaction);

            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(TransactionUpdateRequest $request, $id)
    {
        try {
            $dataTransaction = $request->all();
            $this->transactionService->update($dataTransaction, $id);

            return response()->noContent();
        } catch (\Exception $e) {
            // Captura a exceção lançada pelo transactionService
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $this->transactionService->delete($id);

            return response()->noContent();
        } catch (\Exception $e) {
            // Captura a exceção lançada pelo repository
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
