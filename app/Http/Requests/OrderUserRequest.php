<?php

namespace App\Http\Requests;
use App\Models\AmountProduct;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class OrderUserRequest extends FormRequest {
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
        $products = $this->get('products');

        if(empty($products) ) {
            Session::flash('error', __('orders.need_at_least_one_product'));
        }

        if(empty($this->get('customer_id') ) ){
            Session::flash('error', __('orders.need_customer'));
        }

        $rules = [
            'customer_id' => Order::$rules['customer_id'],
            'products' => 'present|array|min:1',
            'products.*' => AmountProduct::$rules['amount'],
        ];

		return $rules;
	}

}
