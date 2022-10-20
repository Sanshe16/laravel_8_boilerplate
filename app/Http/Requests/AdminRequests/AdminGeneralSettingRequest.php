<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class AdminGeneralSettingRequest extends FormRequest
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
                'first_name' => ['required', 'string', 'min:2', 'max:255'],
                'last_name'  => ['required', 'string', 'min:2', 'max:255'],
                'country'  =>  ['required'],
                'states'  =>  ['required'],
                'city'  =>  ['required'],
                'phone_number'  =>  ['required', 'min:11'],
                'zip_code'  =>  ['required'],
                'address'  =>  ['required'],
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}