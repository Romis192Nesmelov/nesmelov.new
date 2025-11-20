<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EditChapterRequest extends FormRequest
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
            'icon' => $this->validationImage,
            'ru' => 'required|min:2|max:50',
            'en' => 'required|min:2|max:50',
            'image' => $this->validationImage,
            'full_portfolio' => 'nullable|active_url',
            'description_ru' => $this->validationContactString,
            'description_en' => $this->validationContactString,
            'active' => 'nullable'
        ];

        if (request()->has('id')) {
            $validationArr['id'] = $this->validationId.'works';
        }

        return $validationArr;
    }
}
