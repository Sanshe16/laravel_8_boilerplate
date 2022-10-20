<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //Rule::unique('users')->ignore(auth()->id(), 'id')
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' =>  trans('validation.required', ['attribute' => 'first name']),
            'last_name.required' =>  trans('validation.required', ['attribute' => 'last name']),
            'email.required' =>  trans('validation.required', ['attribute' => 'email']),
            'password.required' =>  trans('validation.required', ['attribute' => 'password']),
            'phone_number.required' =>  trans('validation.required', ['attribute' => 'phone number']),
        ];
    }
}
