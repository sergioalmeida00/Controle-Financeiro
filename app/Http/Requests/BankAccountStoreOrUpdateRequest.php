<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountStoreOrUpdateRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'string'],
            'initial_balance' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:CHECKING,INVESTMENT,CASH', 'regex:/^[A-Z]+$/'],
            'color' => ['required', 'string', 'min:3'],
        ];
    }
}
