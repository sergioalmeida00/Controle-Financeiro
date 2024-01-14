<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionsStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bank_account_id' => ['required','exists:bank_accounts,id'],
            'category_id' => ['nullable','exists:categories,id'],
            'name' => ['required', 'min:3', 'string'],
            'value' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:INCOME,EXPENSE', 'regex:/^[A-Z]+$/'],
        ];
    }
}
