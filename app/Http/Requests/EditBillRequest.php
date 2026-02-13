<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use Illuminate\Foundation\Http\FormRequest;

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
            'date' => $this->validationDate,
            'contract' => 'nullable|min:10|max:50000',
            'convention' => 'nullable|min:10|max:50000',
            'act' => 'nullable|min:10|max:50000',
            'bill' => 'nullable|min:10|max:50000',
            'send_email' => 'nullable'
        ];

        if (request()->has('id')) {
            $validationArr['signing'] = 'required|integer|min:1|max:3';
            $validationArr['number'] .= ','.request()->id;
            $validationArr['status'] = 'required|integer|min:1|max:3';
        } else $validationArr['task_id'] = $this->validationTaskId;

        return $validationArr;
    }
}
