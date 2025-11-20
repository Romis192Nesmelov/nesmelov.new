<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EditSettingsRequest extends FormRequest
{
    use HelperTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tax' => 'required|integer|max:90',
            'tax1' => 'required|integer|max:90',
            'tax2' => 'required|integer|max:90',
            'my_percent' => 'required|integer|min:10|max:90',
            'fix_tax' => 'required|integer|min:5000|max:100000',
            'address' => 'required|min:10|max:255',
            'tin' => 'required|size:12',
            'bank_ie' => $this->validationBankName,
            'bank_se' => $this->validationBankName,
            'bank_id_ie' => $this->validationBankId,
            'bank_id_se' => $this->validationBankId,
            'checking_account_ie' => $this->validationCheckingAccount,
            'checking_account_se' => $this->validationCheckingAccount,
            'correspondent_account_ie' => $this->validationCorrespondentAccount,
            'correspondent_account_se' => $this->validationCorrespondentAccount
        ];
    }
}
