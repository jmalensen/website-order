<?php

namespace App\Http\Requests;
use App\Models\AmountProduct;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class OrderAdminRequest extends FormRequest {
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

        if(empty($this->get('products') ) ){
            Session::flash('error', __('orders.need_at_least_one_product'));
        }

        if(empty($this->get('customer_id') ) ){
            Session::flash('error', __('orders.need_customer'));
        }

	    $rules = [
            'user_id' => Order::$rules['user_id'],
            'customer_id' => Order::$rules['customer_id'],
            'date_entered' => Order::$rules['date_entered'],
            'date_closed' => Order::$rules['date_closed'],
            'products' => 'present|array|min:1',
            'products.*' => AmountProduct::$rules['amount'],
        ];

		return $rules;
	}

}
