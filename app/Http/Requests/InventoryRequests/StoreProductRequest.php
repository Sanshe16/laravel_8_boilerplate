<?php

namespace App\Http\Requests\InventoryRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'product_name' => ['required','min:3', 'max:255', 'string'],
            'product_price' => ['required', 'numeric'],
            'purchase_price' => ['required', 'numeric'],
            'is_active' => ['boolean', 'nullable'],
            'product_box' => ['required', 'string', 'min:3', 'max:255'],
            'quantity' => ['required', 'numeric'],
            'sku' => ['required', 'string'],
            'stock_limit' => ['required', 'numeric'],

            'shipping_type_id' => ['required', 'numeric'],
            'stock_vendor_id' => ['required', 'string'],
            'shipping_cost' => ['required', 'numeric'],

            'image.*' => [ 'max:2048', 'nullable'],
            'details' => ['required', 'string'],

            // 'selected_categories' => ['required'],
        ];
    }
}
