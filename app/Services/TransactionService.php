<?php

namespace App\Services;

use App\Models\Transaction;
use App\Services\ValidateBankAccountsOwnership;
use App\Services\ValidateTransactionsOwnership;
use App\Services\ValidateCategoryOwnership;
use App\Services\Traits\ServiceTraits;
use Carbon\Carbon;
use Exception;

class TransactionService
{
    use ServiceTraits;

    protected $repository;
    protected $userId;
    protected $validateBankAccountsOwnership;
    protected $validateTransactionsOwnership;
    protected $validateCategoryOwnership;

    public function __construct(
        Transaction $model,
        ValidateBankAccountsOwnership $validateBankAccountsOwnership,
        ValidateTransactionsOwnership $validateTransactionsOwnership,
        ValidateCategoryOwnership $validateCategoryOwnership
    ) {
        $this->repository = $model;
        $this->userId = $this->getUserAuth();
        $this->validateBankAccountsOwnership = $validateBankAccountsOwnership;
        $this->validateTransactionsOwnership = $validateTransactionsOwnership;
        $this->validateCategoryOwnership = $validateCategoryOwnership;
    }

    public function register($data)
    {
        $this->validateBankAccountsOwnership->validate(
            $data['bank_account_id'],
            $this->userId
        );

        if (isset($data['category_id'])) {
            $this->validateCategoryOwnership->validate(
                $data['category_id'],
                $this->userId
            );
        }

        return $this->repository
            ->create([
                'user_id' => $this->userId,
                'bank_account_id' => $data['bank_account_id'],
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'value' => $data['value'],
                'date' => $data['date'],
                'type' => $data['type']
            ]);
    }

    public function update($data, $idTransaction)
    {
        try {

            $this->validateTransactionsOwnership->validate($idTransaction, $this->userId);

            $transaction = $this->repository
                ->where('user_id', '=', $this->userId)
                ->where('id', '=', $idTransaction)
                ->firstOrFail();

            $transaction->update([
                'user_id' => $this->userId,
                'bank_account_id' => $data['bank_account_id'],
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'value' => $data['value'],
                'date' => $data['date'],
                'type' => $data['type']
            ]);
        } catch (\Exception $exception) {
            throw new \Exception('Transaction not found');
        }
    }

    public function delete($idTransaction)
    {
        try {
            $this->validateTransactionsOwnership->validate($idTransaction, $this->userId);

            $transaction = $this->repository
                ->where('id', '=', $idTransaction)
                ->where('user_id', '=', $this->userId)
                ->firstOrFail();

            $transaction->delete($idTransaction);
        } catch (\Exception $th) {
            throw new \Exception('Transaction not found');
        }
    }

    public function findAllTransactions($filters = [])
    {

        if (isset($filters['bank_account_id'])) {
            $this->validateBankAccountsOwnership->validate(
                $filters['bank_account_id'],
                $this->userId
            );
        }

        return $this->repository
            ->where(function ($query) use ($filters) {
                if (isset($filters['type'])) {
                    $query->where('type', '=', $filters['type']);
                }

                if (isset($filters['month']) && isset($filters['year'])) {
                    $day = $this->getFirstAndLastDayOfMonth($filters['year'], $filters['month']);
                    $query->where('date', '>=', $day['firstDay']);
                    $query->where('date', '<=', $day['lastDay']);
                }

                if (isset($filters['bank_account_id'])) {
                    $query->where('bank_account_id', '=', $filters['bank_account_id']);
                }

                $query->where('user_id', '=', $this->userId);
            })
            ->with('category')
            ->orderBy('date')
            ->get();
    }

    private function getFirstAndLastDayOfMonth($year, $month)
    {
        $firstDay = Carbon::createFromDate($year, $month + 1, 1)->startOfMonth();
        $lastDay = $firstDay->copy()->endOfMonth();


        return [
            'firstDay' => $firstDay->toDateString(),
            'lastDay' => $lastDay->toDateString(),
        ];
    }
}
