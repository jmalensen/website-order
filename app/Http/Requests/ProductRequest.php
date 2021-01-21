<?php

namespace App\Http\Requests;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
            'code' => Product::$rules['code'],
            'name' => Product::$rules['name'],
            'description' => Product::$rules['description'],
            'price_ht' => Product::$rules['price_ht'],
            'rate_vat' => Product::$rules['rate_vat'],
        ];
	}

}
