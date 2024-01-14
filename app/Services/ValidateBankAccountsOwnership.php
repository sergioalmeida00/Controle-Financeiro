<?php

namespace App\Services;

use App\Models\BankAccount;

class ValidateBankAccountsOwnership
{
    protected $validateBankAccountRepo;

    public function __construct(BankAccount $validateBankAccountRepo)
    {
        $this->validateBankAccountRepo = $validateBankAccountRepo;
    }

    public function validate($bankAccountId, $userId)
    {
        $isOwner = $this->validateBankAccountRepo
            ->where('id', '=', $bankAccountId)
            ->where('user_id', '=', $userId)
            ->first();

        if (!$isOwner) {
            throw new \Exception('Bank account not found');
        }
    }
}
