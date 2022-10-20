<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ];
    }

     /**
     * Get the validation messages that apply to the request.
    *
    * @return array
    */
    public function messages()
    {
        // use trans instead on Lang 
        return [
            'email.required' => trans('validation.required', ['attribute' => 'email']),
            'password.required' => trans('validation.required', ['attribute' => 'password']),
        ];
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     $this->validator = $validator;
    // }
}
