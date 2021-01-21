<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderAdminRequest;

use App\Mail\UpdateOnOrder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request) {
		$orders = Order::sortable(['date_entered' => 'desc'])
			->with(['customer', 'user', 'groupe'])
			->resetPaginate($request->only(['search']))
            ->paginate(20);

		return view('ordersadmin.index', compact('orders'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$order = new Order();

        $products = Product::all();
        foreach($products as $product){
            $product->setAttribute('amount', 0);
        }

        $usersT = User::approvisionneurs()->get();
        // If there is no user_appro
        if($usersT->count() <= 0){
            Session::flash('error', __('orders.error_need_user_appro_first'));
            return redirect()->route('users.create');
        }

        // KeyBinding (idUser => user, relation(idCustomer => customer) )
        $users = User::approvisionneurs()
            ->with($relations = ['customers'])
            ->get()
            ->keyBy('id')
            ->each(function($user) use($relations) {
                foreach($relations as $relation) {
                    $user->setRelation($relation, $user->$relation->keyBy('id'));
                }
            });

        $selectedUser = $users->first()->id;

        return view('ordersadmin.create', compact('order', 'products', 'users', 'selectedUser'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param OrderAdminRequest $request
	 * @param Order $order
	 * @return Response
	 */
	public function store(OrderAdminRequest $request, Order $order) {
    	$data = $request->all();

        $user = User::findOrFail($data['user_id']);
        $customer = Customer::findOrFail($data['customer_id']);

    	// Handle order
        $order->code = uniqid('C_');
        $order->customer_id = $customer->id;
    	$order->user_id = $user->id;
    	$order->groupe_id = $customer->groupe_id;

    	if( !empty($data['date_entered']) ){
            $order->date_entered = $data['date_entered'];
        } else{
            $order->date_entered = Carbon::now();
        }

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
            return redirect()->route('admin.orders.index');
		}
		return redirect()->route('admin.orders.view', ['order' => $order]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  Order  $order
	 * @return Response
	 */
	public function view(Order $order) {
        $amountProducts = $order->amountProducts->load('product');

		return view('ordersadmin.view', compact('order', 'amountProducts'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Order $order
	 * @return Response
	 */
	public function edit(Order $order) {

        // See added products
        $productsAdded = $order->amountProducts()->with('product')->get();

        $products = Product::all();
        foreach($products as $product){
            $product->setAttribute('amount', 0);
        }

        // KeyBinding (idUser => user, relation(idCustomer => customer) )
        $users = User::approvisionneurs()
            ->with($relations = ['customers'])
            ->get()
            ->keyBy('id')
            ->each(function($user) use($relations) {
                foreach($relations as $relation) {
                    $user->setRelation($relation, $user->$relation->keyBy('id'));
                }
            });

        $selectedUser = $order->user_id;
        $selectedCustomer = $order->customer_id;

		return view('ordersadmin.edit', compact('order', 'users', 'products', 'productsAdded', 'selectedUser', 'selectedCustomer'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param Order $order
	 * @param OrderAdminRequest $request
	 * @return Response
	 */
	public function update(OrderAdminRequest $request, Order $order) {
		$data = $request->all();

        // If change of user
        if(!empty($data['user_id'])
            && $order->customer_id != $data['user_id'] ) {

            $user = User::findOrFail($data['user_id']);
            $order->user_id = $user->id;
        }

        // If change of customer
        if(!empty($data['customer_id'])
            && $order->customer_id != $data['customer_id'] ){

            $customer = Customer::findOrFail($data['customer_id']);

            // Handle order
            $order->customer_id = $customer->id;
            $order->groupe_id = $customer->groupe_id;
        }

        if( !empty($data['date_entered']) ){
            $order->date_entered = $data['date_entered'];
        }

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
            return redirect()->route('admin.orders.view', ['order' => $order]);
        }
        Session::flash('error', __('commun.error'));
        return $this->edit($order);








//        //Remove amountProducts when not in update
//        $order->amountProducts()
//            ->whereNotIn('product_id', array_keys($data['products']))
//            ->delete();
//
//        // Handle amount by product
//        $products = Product::whereIn('id', array_keys($data['products']))->get();
//        foreach($products as $product){
//
//            $amountProduct = $order->getAmountProductForSpecified($product->id);
//            $newAmount = $data['products'][$product->id];
//            if(empty($amountProduct)){
//                $order->amountProducts()->create(
//                    [
//                        'amount'=> $newAmount,
//                        'product_id' => $product->id,
//                        'total_price' => ($newAmount * $product->price_ht),
//                        'customer_id' => $order->customer_id
//                    ]
//                );
//            } else{
//
//                $amountProduct->update(
//                    [
//                        'amount'=> $newAmount,
//                        'total_price' => ($newAmount * $product->price_ht),
//                        'customer_id' => $order->customer_id
//                    ]
//                );
//            }
//        }
//
//		if ($order->update()){
//			Session::flash('success', __('orders.updated'));
//            return redirect()->route('admin.orders.index');
//		}
//        Session::flash('error', __('commun.error'));
//		return $this->edit($order);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Order $order
	 * @return Response
	 * @throws \Exception
	 */
	public function delete(Order $order) {
        $order->amountProducts()->delete();

		if ($order->delete()) {
			Session::flash('success', __('orders.deleted'));
		} else {
            Session::flash('error', __('commun.error'));
		}
		return redirect()->route('admin.orders.index');
	}


    /**
     * Close order
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function close(Request $request, Order $order){
        $order->status = Order::STATUS_CLOSED_ORDER;
        $order->date_closed = Carbon::now();
        if($order->save()){

            // Send mail to user when order is changed
            $emailUser = $order->user->email;
            if ($emailUser){
                Mail::to($emailUser)
                    ->queue(new UpdateOnOrder($order));
            }

            Session::flash('success', __('orders.updated'));
        } else{
            Session::flash('error', __('commun.error'));
        }
        return redirect()->route('admin.orders.index');
    }


    /**
     * Pause order
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pause(Request $request, Order $order){
        $order->status = Order::STATUS_TROUBLE_ORDER;
        if($order->save()){

            // Send mail to user when order is changed
            $emailUser = $order->user->email;
            if ($emailUser){
                Mail::to($emailUser)
                    ->queue(new UpdateOnOrder($order));
            }

            Session::flash('success', __('orders.updated'));
        } else{
            Session::flash('error', __('commun.error'));
        }
        return redirect()->route('admin.orders.index');
    }


    /**
     * Cancel order
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, Order $order){
        $order->status = Order::STATUS_CANCELED_ORDER;
        if($order->save()){

            // Send mail to user when order is changed
            $emailUser = $order->user->email;
            if ($emailUser){
                Mail::to($emailUser)
                    ->queue(new UpdateOnOrder($order));
            }

            Session::flash('success', __('orders.updated'));
        } else{
            Session::flash('error', __('commun.error'));
        }
        return redirect()->route('admin.orders.index');
    }


    /**
     * Mass modify
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function massmodify(Request $request){
        $orders = Order::sortable(['date_entered' => 'desc'])
            ->with(['customer', 'user'])
            ->resetPaginate($request->only(['search']))
            ->paginate(20);

        $actions = [
            Order::STATUS_TROUBLE_ORDER => Order::getStatusLibelle(Order::STATUS_TROUBLE_ORDER),
            Order::STATUS_CLOSED_ORDER => Order::getStatusLibelle(Order::STATUS_CLOSED_ORDER),
            Order::STATUS_CANCELED_ORDER => Order::getStatusLibelle(Order::STATUS_CANCELED_ORDER),
            Order::STATUS_IN_PROGRESS_ORDER => Order::getStatusLibelle(Order::STATUS_IN_PROGRESS_ORDER),
        ];

        return view('ordersadmin.massmodify', compact('orders', 'actions'));
    }


    /**
     * Store new status on orders
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storemassmodification(Request $request){
        $data = $request->all();

        if(!empty($data['order']) ){
			$orders = Order::whereIn('id', $data['order'])->get();
            foreach($orders as $order){

                // Modify status if different only
                if($order->status != $data['new_status']) {
                    $order->status = $data['new_status'];

                    // If closing status
                    if ($data['new_status'] == Order::STATUS_CLOSED_ORDER) {
                        $order->date_closed = now();
                    }

                    // Send mail to user when order is changed
                    $emailUser = $order->user->email;
                    if ($emailUser) {
                        Mail::to($emailUser)
                            ->queue(new UpdateOnOrder($order));
                    }

                    if ($order->update()) {
                        Session::flash('success', __('orders.massmodify_done'));
                    } else {
                        Session::flash('error', __('orders.error_to_modify') . ' ' . $order->id);
                    }
                }
            }
        } else{
            Session::flash('error', __('orders.need_to_select_orders'));
        }

        return redirect()->route('admin.orders.massmodify');
    }
}
