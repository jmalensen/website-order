<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\AmountProduct;
use App\Models\Groupe;
use App\Models\Product;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request) {
		$products = Product::sortable(['code' => 'asc'])
            ->with(['groupe'])
			->resetPaginate($request->only(['search']))
			->orderBy('code')
			->paginate(20);

		return view('products.index', compact('products'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$product = new Product();
        $groupes = Groupe::all()->pluck('name', 'id');
		return view('products.create', compact('product', 'groupes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param ProductRequest $request
	 * @param Product $product
	 * @return Response
	 */
	public function store(ProductRequest $request, Product $product) {
    	$data = $request->all();
		$product->fill($data);

		if ($product->save()){
			Session::flash('success', __('products.created'));
		}else{
            Session::flash('error', __('commun.error'));
            return redirect()->route('products.index');
		}
        return redirect()->route('products.view', ['product' => $product]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Product  $product
	 * @return Response
	 */
	public function view(Product $product) {
		$qtecde = $product->cloturedAmountProducts()->sum('amount');
		$qtequery = $product->cloturedAmountProducts()->with(['order']);
		
		// récupération de chaque mois de la période
		$datemin = $qtequery->get()->min('order.date_entered');
		$allmonth = collect();
		if (!empty($datemin)){
			$period = CarbonPeriod::create($datemin, '1 day', today());
			// Iterate over the period
			foreach ($period as $date) {
				$allmonth->put($date->format('Y-m-d'), 0);
			}
		}
		$qteByDays = $qtequery->get()->groupBy(function(AmountProduct $a) {
			 return $a->order->date_entered->format('Y-m-d');
		})->map(function($items, $key){
			return $items->sum('amount');
		})->sortKeys();
		// pour avoir tous les mois de la période
		$qteByDays = $qteByDays->union($allmonth)->sortBy(function($items, $key){
			return $key;
		});
		return view('products.view', compact('product', 'qtecde', 'qteByDays'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Product $product
	 * @return Response
	 */
	public function edit(Product $product) {
        $groupes = Groupe::all()->pluck('name', 'id');
		return view('products.edit', compact('product', 'groupes'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Product $product
	 * @param ProductRequest $request
	 * @return Response
	 */
	public function update(ProductRequest $request, Product $product) {
		$data = $request->all();

		if ($product->update($data)){
			Session::flash('success', __('products.updated'));
            return redirect()->route('products.view', ['product' => $product]);
		}
        Session::flash('error', __('commun.error'));
		return $this->edit($product);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Product $product
	 * @return Response
	 * @throws \Exception
	 */
	public function delete(Product $product) {
		if ($product->delete()) {
			Session::flash('success', __('products.deleted'));
		} else {
            Session::flash('error', __('commun.error'));
		}
		return redirect()->route('products.index');
	}

}
