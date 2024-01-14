<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Services\ValidateBankAccountsOwnership;
use App\Services\Traits\ServiceTraits;
use Exception;

class BankAccountService
{
    use ServiceTraits;
    protected $repository;
    protected $userId;
    protected $validateBankAccountsOwnership;

    public function __construct(BankAccount $model, ValidateBankAccountsOwnership $validateBankAccountsOwnership)
    {
        $this->repository = $model;
        $this->userId = $this->getUserAuth();
        $this->validateBankAccountsOwnership = $validateBankAccountsOwnership;
    }

    public function register($data)
    {
        $data['user_id'] = $this->userId;
        return $this->repository->create($data);
    }

    public function update($data, $idBankAccount)
    {
        try {
            $bankAccount = $this->repository
                ->where('user_id', '=', $this->userId)
                ->where('id', '=', $idBankAccount)
                ->firstOrFail();

            $bankAccount->update([
                'name' => $data['name'],
                'initial_balance' => $data['initial_balance'],
                'type' => $data['type'],
                'color' => $data['color']
            ]);
        } catch (Exception $exception) {
            // Lança uma exceção indicando que a conta bancária não foi encontrada
            throw new \Exception('Bank account not found');
        }
    }

    public function delete($idBankAccount)
    {
        try {
            $bankAccount = $this->findAllTransactionBankAccount($idBankAccount);

            if ($bankAccount->transactions->isNotEmpty()) {
                throw new \Exception('Bank account has transactions');
            }

            $this->repository
                ->where('user_id', '=', $this->userId)
                ->where('id', '=', $idBankAccount)
                ->delete();
        } catch (\Exception $exception) {
            throw new \Exception('Bank account has transactions');
        }
    }

    public function findAllTransactionBankAccount($idBankAccount)
    {
        $this->validateBankAccountsOwnership->validate($idBankAccount, $this->userId);

        $transactions = $this->repository
            ->where('user_id', '=', $this->userId)
            ->where('id', '=', $idBankAccount)
            ->with('transactions')
            ->first();

        return $transactions;
    }
}
