<?php

namespace App\Http\Requests;

use App\Http\Controllers\HelperTrait;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EditTaskRequest extends FormRequest
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
            'email' => $this->validationEmail,
            'phone' => 'nullable|'.$this->validationPhone,
            'contact_person' => $this->validationContactString,
            'convention_number' => 'nullable|integer|max:1000',
            'value' => $this->validationValue,
            'paid_off' => 'integer|max:2000000',
            'percents' => $this->validationValue,
            'start_time' => $this->validationDate,
            'completion_time' => $this->validationDate,
            'payment_time' => $this->validationDate,
            'use_payment_time' => 'nullable',
            'description' => 'nullable|min:10|max:10000',
            'user_id' => $this->validationId.'users,id',
            'customer_id' => $this->validationCustomerId,
            'status' => 'required|integer|min:1|max:5',
            'send_email' => 'nullable',
            'save_convention' => 'nullable'
        ];

        if (request()->has('id')) $validationArr['id'] = $this->validationTaskId;
        if (Gate::allows('is-admin')) {
            $validationArr['owner_id'] = $this->validationId.'users,id';
            $validationArr['paid_percents'] = 'in:0,1';
            $validationArr['use_duty'] = 'nullable';
        }

        $customer = Customer::query()->where('id', request()->customer_id)->select('ltd')->first();
        if ($customer->ltd != 2) $validationArr['convention_date'] = $this->validationDate;

        return $validationArr;
    }
}
