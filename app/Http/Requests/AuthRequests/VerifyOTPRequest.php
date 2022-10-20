<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOTPRequest extends FormRequest
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
            'email' => 'required|email|max:255',
            'otp' => 'required|numeric|digits:6',
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
            'otp.required' =>  trans('validation.required', ['attribute' => 'otp']),
            'email.required' =>  trans('validation.required', ['attribute' => 'email']),
        ];
    }

}
