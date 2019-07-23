<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'total_price' => 'required|integer|min:0',
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255|nullable',
            'address1' => 'required|string|max:255',
            'address2' => 'string|max:255|nullable',
            'country' => 'required|string|max:255',
            'state' => 'string|max:255|nullable',
            'city' => 'required|string|max:255',
            'item_id_array' => 'required|array',
            'item_id_array.*' => 'integer|min:1',
            'item_qty_array.*' => 'required_with:item_id_array.*|integer|min:1',
            'item_price_array.*' => 'required_with:item_id_array.*|integer|min:0',
        ];
    }
}
