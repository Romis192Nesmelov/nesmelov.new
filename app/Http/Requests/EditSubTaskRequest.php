<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EditSubTaskRequest extends FormRequest
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
            'name' => $this->validationName,
            'value' => $this->validationValue,
            'percents' => 'max:100',
            'start_time' => $this->validationDate,
            'completion_time' => $this->validationDate,
            'description' => 'nullable|min:10|max:2000',
            'send_email' => 'nullable'
        ];

        if (request()->has('id')) {
            $validationArr['id'] = $this->validationId.'sub_tasks';
            $validationArr['status'] = 'required|integer|min:1|max:5';
        } else {
            $validationArr['parent_id'] = $this->validationId.'tasks,id';
            $validationArr['status'] = 'required|integer|min:3|max:5';
        }

        return $validationArr;
    }
}
