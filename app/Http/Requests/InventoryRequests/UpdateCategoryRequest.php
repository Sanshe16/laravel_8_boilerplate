<?php

namespace App\Http\Requests\InventoryRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
            'name' => ['required', 'min:2', 'string',  Rule::unique('categories')->where(fn ($query) => $query->where('id', '!=', $this->category->id)->where('deleted_at', null))],
            'image' => ['mimes:jpeg,png,jpg,gif,svg', 'max:2048', 'nullable'],
            'parent_id' => ['integer', 'nullable'],
            'is_active' => ['boolean', 'nullable'],
        ];
    }
}
