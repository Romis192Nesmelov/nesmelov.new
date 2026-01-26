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

        if (request()->has('branch_id') && request()->branch_id == 2) {
            $validationArr['url'] = 'required|unique:works,url';
        } elseif(request()->has('id'))  {
            $validationArr['full'] = $this->validationImageRequired;
        }

        if (request()->has('id')) {
            $validationArr['id'] = $this->validationId.'works';
            $validationArr['preview'] = $this->validationImageRequired;
            if (isset($validationArr['url'])) $validationArr['url'] .= ','.request()->id;
        } else {
            $validationArr['preview'] = $this->validationImage;

            if (request()->has('branch_id') && request()->branch_id != 2 && request()->branch_id != 5)
                $validationArr['full'] = $this->validationImage;
        }

        return $validationArr;
    }
}
