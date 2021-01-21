<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Http\Requests\OrderUserRequest;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller {


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index(Request $request) {
		$orders = Order::sortable(['date_entered' => 'desc'])
            ->resetPaginate($request->only(['search']))
            ->with(['customer'])
            ->orderBy('date_entered', 'desc')
			->paginate(20);

		// Can only make new order before 11:00 (default)
		$canDoOrder = $this->_canDoOrder();

		return view('ordersmember.index', compact('orders', 'canDoOrder'));
	}


    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
	public function create() {

        if (!$this->_canDoOrder()){
            return redirect()->route('member.orders.index');
        }

		$order = new Order();

		$products = Product::all();
		foreach($products as $product){
            $product->setAttribute('amount', 0);
        }

        // List customers available for an order (user)
        $customers = Auth::user()->customers()->pluck('company_name', 'customers.id');

        return view('ordersmember.create', compact('order', 'customers', 'products'));
	}


    /**
     * Store a newly created resource in storage.
     * @param OrderUserRequest $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(OrderUserRequest $request, Order $order) {
    	$data = $request->all();

        // Can only make new order before 11:00
        if (!$this->_canDoOrder()){
            Session::flash('error', __('commun.error'));
            return redirect()->route('member.orders.index');
        }

        // Handle order
        $order->code = uniqid('C_');
        $order->date_entered = Carbon::now();
        $order->customer_id = $data['customer_id'];

		if ($order->save()){

            // Handle amount by product
            $products = Product::whereIn('id', array_keys($data['products']))->get();
            foreach($products as $product){
                $amount = $data['products'][$product->id];
                $order->amountProducts()->create(
                    [
                        'amount'=> $amount,
                        'product_id' => $product->id,
                        'total_price' => ($amount * $product->price_ht),
                        'customer_id' => $order->customer_id
                    ]
                );
            }

            Session::flash('success', __('orders.created'));
		}else{
            Session::flash('error', __('commun.error'));
            return redirect()->route('member.orders.index');
        }
        return redirect()->route('member.orders.view', ['order' => $order]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  Order  $order
	 * @return Response
	 */
	public function view(Order $order) {
        $amountProducts = $order->amountProducts->load('product');

        // Can only make new order before 11:00
        $canDoOrder = $this->_canDoOrder();

        if($order->status != Order::STATUS_ACTIVE_ORDER){
            $canDoOrder = false;
        }

		return view('ordersmember.view', compact('order', 'amountProducts', 'canDoOrder'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Order $order
	 * @return Response
	 */
	public function edit(Order $order) {
        // Can only make new order before 11:00
        if (!$this->_canDoOrder()){
            return redirect()->route('member.orders.index');
        }

        // See added products
        $productsAdded = $order->amountProducts()->with('product')->get();

        $products = Product::all();
        foreach($products as $product){
            $product->setAttribute('amount', 0);
        }
        // List customers available for an order (user)
        $customers = Auth::user()->customers()->pluck('company_name', 'customers.id');

        return view('ordersmember.edit', compact('order', 'customers', 'products', 'productsAdded'));

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param Order $order
	 * @param OrderUserRequest $request
	 * @return Response
	 */
	public function update(OrderUserRequest $request, Order $order) {
		$data = $request->all();

        $order->customer_id = $data['customer_id'];

        // Remove all amountproducts first
        $order->amountProducts()->delete();

        // Handle amount by product
        $products = Product::whereIn('id', array_keys($data['products']))->get();
        foreach($products as $product){

            $newAmount = $data['products'][$product->id];
            $order->amountProducts()->create(
                [
                    'amount'=> $newAmount,
                    'product_id' => $product->id,
                    'total_price' => ($newAmount * $product->price_ht),
                    'customer_id' => $order->customer_id
                ]
            );
        }

        if ($order->update()){
            Session::flash('success', __('orders.updated'));
            return redirect()->route('member.orders.view', ['order' => $order]);
        }
        Session::flash('error', __('commun.error'));
        return $this->edit($order);
	}


    /**
     * Remove the specified resource from storage.
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
	public function delete(Order $order) {
        $order->amountProducts()->delete();

		if ($order->delete()) {
			Session::flash('success', __('orders.deleted'));
		} else {
            Session::flash('error', __('commun.error'));
		}
		return redirect()->route('member.orders.index');
	}


    /**
     * Duplicate old order
     * @param Request $request
     * @param Order $order
     * @return bool|\Illuminate\Http\RedirectResponse
     */
	public function duplicate(Request $request, Order $order){

	    $now = Carbon::now();
        if($now->diffInDays($order->date_closed) > config('app.daysForDuplication')){
            return false;
        }

        $newOrder = $order->replicate();

        // Handle order
        $newOrder->status = Order::STATUS_ACTIVE_ORDER;
        $newOrder->code = uniqid('C_');
        $newOrder->date_entered = Carbon::now();
        $newOrder->date_closed = null;

        if($newOrder->save() ){

            foreach($order->amountProducts as $amountProduct){
                $newOrder->amountProducts()->create([
                    'amount' => $amountProduct->amount,
                    'total_price' => $amountProduct->total_price,
                    'order_id' => $newOrder->id,
                    'product_id' => $amountProduct->product_id,
                ]);
            }

            Session::flash('success', __('orders.duplicated'));
        } else{
            Session::flash('error', __('commun.error'));
        }
        return redirect()->route('member.orders.index');
    }


    /**
     * Check if an order can be registered
     * @return bool
     */
	private function _canDoOrder(){

        // Can only make new order before lastTimeToOrder
        return now()->format('H:i:s') < config('app.lastTimeToOrder');
    }


    /**
     * Search products
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProducts(Request $request){

        $this->isAjaxOrFail($request);
        if(!$request->has('search') ){
            return $this->ajaxDataMissing();
        }

        $products = Product::sortable()
            ->resetPaginate($request->only(['search']))
            ->orderBy('name')
            ->get();

        foreach($products as $product){
            $product->setAttribute('amount', 0);
        }

        return response()->json(['success' => true, 'data' => ['products' => $products]]);
    }


    /**
     * Store losses.
     * @param Request $request
     * @param Order $order
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function storeLosses(Request $request, Order $order) {
        $data = $request->all();

        $result = false;
        // If there are any losses change of product
        // Handle losses by product
        foreach($data['products'] as $productId => $loss){

            // If negative loss
            if($loss < 0){
                Session::flash('error', __('orders.loss_cant_be_negative'));
                return redirect()->route('member.orders.view', ['order' => $order]);
            }

            $amountProduct = $order->getAmountProductForSpecified($productId);

            // If over loss
            if($loss > $amountProduct->amount){
                Session::flash('error', __('orders.loss_cant_be_more'));
                return redirect()->route('member.orders.view', ['order' => $order]);
            }

            $result = $order->getAmountProductForSpecified($productId)->update(
                [
                    'losses'=> $loss
                ]
            );
        }

        if ($result){
            Session::flash('success', __('orders.saved_losses'));
        }else{
            Session::flash('error', __('commun.error'));
        }
        return redirect()->route('member.orders.index');
    }
}
