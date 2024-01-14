<?php

namespace App\Services;

use App\Models\Transaction;

class ValidateTransactionsOwnership
{
    protected $validateTransactionRepo;

    public function __construct(Transaction $validateTransactionRepo)
    {
        $this->validateTransactionRepo = $validateTransactionRepo;
    }

    public function validate($transactionId, $userId)
    {
        $isOwner = $this->validateTransactionRepo
            ->where('id', '=', $transactionId)
            ->where('user_id', '=', $userId)
            ->first();

        if (!$isOwner) {
            throw new \Exception('Transaction Not Found.');
        }
    }
}
