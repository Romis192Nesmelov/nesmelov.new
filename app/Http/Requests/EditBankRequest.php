<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EditBankRequest extends FormRequest
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
            'name' => 'required|max:255|unique:banks,name',
            'bank_id' => 'required|size:9|unique:banks,bank_id'
        ];

        if (request()->has('id')) {
            $validationArr['id'] = $this->validationId . 'banks';
            $validationArr['name'] .= ',' . request()->id;
            $validationArr['bank_id'] .= ',' . request()->id;
        }

        return $validationArr;
    }
}
