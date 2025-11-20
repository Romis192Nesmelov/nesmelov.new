<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EditUserRequest extends FormRequest
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
            'name' => 'required|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|'.$this->validationPhone,
        ];

        if (Gate::allows('is-admin')) $validationArr['is_admin'] = 'nullable';

        if (request()->has('id')) {
            $validationArr['id'] = $this->validationId.'users';
            $validationArr['email'] .= ','.request()->id;
            $validationArr['name'] .= ','.request()->id;

            if (request()->password) {
                $validationArr['password'] = $this->validationPassword;
                if (Gate::denies('is-admin')) $validationArr['old_password'] = 'required|min:3|max:50';
            }
        } else {
            $validationArr['password'] = $this->validationPassword;
        }

        return $validationArr;
    }
}
