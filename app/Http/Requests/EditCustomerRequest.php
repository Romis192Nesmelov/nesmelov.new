<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use Illuminate\Foundation\Http\FormRequest;

class EditCustomerRequest extends FormRequest
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
        $validationArr = [
            'type' => 'required||min:1|max:5',
            'ltd' => 'min:0|max:3',
            'name' => 'required|max:255|unique:customers,name',
            'phone' => 'nullable|'.$this->validationPhone,
            'email' => 'nullable|email',
            'contact_person' => $this->validationString,
            'description' => 'max:2000',
            'director' => 'max:255',
            'director_case' => 'max:255',
            'address' => 'max:255',
            'okved' => 'max:255',
            'bank_id' => $this->validationId.'banks,id',
            'contract_number' => 'max:45',
            'contract_date' => $this->validationDate,
            'ogrn' => 'nullable|min:11|max:15',
            'okpo' => 'nullable|min:8|max:10',
            'oktmo' => 'nullable|max:8',
            'inn' => 'nullable|max:12',
            'kpp' => 'nullable|max:9',
            'payment_account' => 'nullable|size:20',
            'correspondent_account' => 'nullable|size:20',
            'contract' => 'nullable|min:10|max:50000',
        ];

        if (request()->has('id')) {
            $validationArr['id'] = $this->validationId . 'customers';
            $validationArr['name'] .= ',' . request()->id;
        }

        return $validationArr;
    }
}
