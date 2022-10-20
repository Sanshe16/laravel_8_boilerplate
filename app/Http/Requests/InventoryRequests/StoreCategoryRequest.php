<?php

namespace App\Http\Requests\InventoryRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => ['required','min:2', 'string', 'unique:categories,name,NULL,id,deleted_at,NULL'],
            'image' => ['mimes:jpeg,png,jpg,gif,svg', 'max:2048', 'nullable'],
            'parent_id' => ['integer', 'nullable'],
            'is_active' => ['boolean', 'nullable'],
        ];
    }
}
