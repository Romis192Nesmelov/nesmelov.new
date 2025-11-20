<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EditBillRequest extends FormRequest
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
            'number' => $this->validationBillNumber,
            'signing' => 'required|integer|min:1|max:3',
            'date' => $this->validationDate,
            'contract' => 'nullable|min:10|max:50000',
            'save_contract' => 'nullable',
            'convention' => 'nullable|min:10|max:50000',
            'save_convention' => 'nullable',
            'act' => 'nullable|min:10|max:50000',
            'save_act' => 'nullable',
            'bill' => 'nullable|min:10|max:50000',
            'save_bill' => 'nullable',
        ];

        if (request()->has('id')) {
            $validationArr['number'] .= ','.request()->id;
            $validationArr['status'] = 'required|integer|min:1|max:3';
        } else $validationArr['task_id'] = $this->validationTaskId;

        return $validationArr;
    }
}
