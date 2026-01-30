<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EditWorkRequest extends FormRequest
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
            'branch_id' => $this->validationId.'branches,id',
            'name_ru' => $this->validationName,
            'name_en' => $this->validationName,
            'description_ru' => $this->validationName,
            'description_en' => $this->validationName,
            'active' => 'nullable'
        ];

        if (request()->has('branch_id')) {
            if (request()->branch_id == 2) $validationArr['url'] = 'required|unique:works,url';
            elseif (request()->branch_id == 5) $validationArr['url'] = $this->validationPDF;
        }

        if (request()->has('id')) {
            $validationArr['id'] = $this->validationId.'works';
            $validationArr['preview'] = 'nullable|'.$this->validationImage;

            if (request()->has('branch_id')) {
                if (request()->branch_id != 2) $validationArr['full'] = 'nullable|'.$this->validationImage;
                else $validationArr['url'] .= ','.request()->id;
            }

        } else {
            $validationArr['preview'] = 'required|'.$this->validationImage;
            if (request()->has('branch_id') && request()->branch_id != 2) $validationArr['full'] = 'required|'.$this->validationImage;
        }

        return $validationArr;
    }
}
