<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $totalTransactions = 0;

        if ($this->transactions) {
            foreach ($this->transactions as $transaction) {
                $totalTransactions += ($transaction['type'] === 'INCOME' ? $transaction['value'] : -$transaction['value']);
            }
        }
        $currentBalance = $this->initial_balance + $totalTransactions;

        return [
            'responseValue' => $this->resource,
            'totalTransactions' => round($totalTransactions, 2),
            'currentBalance' => $currentBalance
        ];
    }
}
