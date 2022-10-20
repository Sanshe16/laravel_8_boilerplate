<?php

namespace App\Http\Requests\UserRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
//            'name' => ['required','min:6','max:32', 'regex:/^(?![_.0-9])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/', Rule::unique('users')->ignore(auth()->id(), 'id')],
            'username' => ['required','min:2','max:50', Rule::unique('users')->ignore(auth()->id(), 'id')],
            'first_name' => ['required', 'string','min:2', 'max:30',],
            'last_name' => ['required', 'string', 'min:2','max:30',],
            'dob' =>
            [
                'nullable',
                'date_format:Y/m/d',
                'before_or_equal:' . date('Y/m/d'),
            ],
            'phone_number' => 'nullable|numeric',
            // 'web_site' =>'url',
        ];
    }

    public function messages()
    {
        return [
            'username.required' =>  trans('validation.required', ['attribute' => 'username']),
            'first_name.required' => trans('validation.required', ['attribute' => 'first name']),
            'last_name.required' => trans('validation.required', ['attribute' => 'last name']),
        ];
    }
}
