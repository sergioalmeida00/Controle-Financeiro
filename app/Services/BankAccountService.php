<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Transaction;
use App\Services\ValidateBankAccountsOwnership;
use App\Services\Traits\ServiceTraits;
use Exception;
use Illuminate\Support\Facades\DB;

class BankAccountService
{
    use ServiceTraits;
    protected $repository;
    protected $userId;
    protected $validateBankAccountsOwnership;
    protected $transactionRepository;

    public function __construct(BankAccount $model, ValidateBankAccountsOwnership $validateBankAccountsOwnership, Transaction $transactionRepository)
    {
        $this->repository = $model;
        $this->userId = $this->getUserAuth();
        $this->validateBankAccountsOwnership = $validateBankAccountsOwnership;
        $this->transactionRepository = $transactionRepository;
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

            if (!$bankAccount) {
                throw new \Exception('Bank account not found');
            }

            //INICIA PARA DELETAR AS TRANSAÇÕES CASO EXISTE E A CONTA BANCARIA;
            DB::beginTransaction();

            if ($bankAccount->transactions->isNotEmpty()) {
                $this->transactionRepository
                    ->where('user_id', '=', $this->userId)
                    ->where('bank_account_id', '=', $idBankAccount)
                    ->delete();
            }

            $this->repository
                ->where('user_id', '=', $this->userId)
                ->where('id', '=', $idBankAccount)
                ->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            throw new \Exception('Failed to delete bank account');
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

    public function getAllBankAccount()
    {
        $responseBankAccounts = $this->repository
            ->where('user_id', '=', $this->userId)
            ->with('transactions.category')
            ->orderBy('name')
            ->get();

        foreach ($responseBankAccounts as $key => $account) {
            $totalTransactions = 0;

            foreach ($account->transactions as $key => $transaction) {
                if (!empty($transaction)) {
                    $totalTransactions += ($transaction['type'] === 'INCOME' ? $transaction['value'] : -$transaction['value']);
                }
            }

            $account->currentBalance = $account->initial_balance + $totalTransactions;
        }

        return $responseBankAccounts;
    }
}
