<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProfileUpdateRequest extends FormRequest
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
        try {

            return [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  =>  ['required', 'string', 'max:255'],
                'phone_number'  =>  ['nullable', 'min:11'],
                'gender' =>  'nullable',
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
