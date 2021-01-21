<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AmountProductRequest;

use App\Models\AmountProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AmountProductController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request) {
		$amount_products = AmountProduct::sortable()
			->resetPaginate($request->only(['search']))
			->orderBy('created_at')
			->paginate(20);

		return view('amount_products.index', compact('amount_products'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$amount_product = new AmountProduct();
		return view('amount_products.create', compact('amount_product'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param AmountProductRequest $request
	 * @param AmountProduct $amount_product
	 * @return Response
	 */
	public function store(AmountProductRequest $request, AmountProduct $amount_product) {
    	$data = $request->all();
		$amount_product->fill($data);

		if ($amount_product->save()){
			Session::flash('success', __('amount_products.created'));
		}else{
            Session::flash('error', __('commun.error'));
		}
		return redirect()->home();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  AmountProduct  $amount_product
	 * @return Response
	 */
	public function view(AmountProduct $amount_product) {
		return view('amount_products.view', compact('amount_product'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  AmountProduct $amount_product
	 * @return Response
	 */
	public function edit(AmountProduct $amount_product) {
		return view('amount_products.edit', compact('amount_product'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param AmountProduct $amount_product
	 * @param AmountProductRequest $request
	 * @return Response
	 */
	public function update(AmountProductRequest $request, AmountProduct $amount_product) {
		$data = $request->all();

		if ($amount_product->update($data)){
			Session::flash('success', __('amount_products.updated'));
			return redirect()->home();
		}
        Session::flash('error', __('commun.error'));
		return $this->edit($amount_product);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param AmountProduct $amount_product
	 * @return Response
	 * @throws \Exception
	 */
	public function delete(AmountProduct $amount_product) {
		if ($amount_product->delete()) {
			Session::flash('success', __('amount_products.deleted'));
		} else {
            Session::flash('error', __('commun.error'));
		}
		return redirect()->route('amount_products.index');
	}

}
