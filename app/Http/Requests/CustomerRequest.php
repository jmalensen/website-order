<?php

namespace App\Http\Requests;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest {
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
            'company_name' => Customer::$rules['company_name'],
            'address' => Customer::$rules['address'],
            'address_complement' => Customer::$rules['address_complement'],
            'city' => Customer::$rules['city'],
            'postcode' => Customer::$rules['postcode'],
            'country' => Customer::$rules['country'],
            'phone' => Customer::$rules['phone'],
            'user_id' => Customer::$rules['user_id'],
        ];
	}

}
