<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;

use App\Models\Customer;
use App\Models\Groupe;
use App\Models\UsersCustomer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request) {
		$customers = Customer::sortable([
		    'company_name' => 'asc'
        ])
            ->with(['groupe', 'user'])
			->resetPaginate($request->only(['search']))
			->orderBy('company_name')
			->paginate(20);

		return view('customers.index', compact('customers'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$customer = new Customer();
        $users = User::approvisionneurs()->get()->pluck('libelle', 'id');
        $groupes = Groupe::all()->pluck('name', 'id');
		return view('customers.create', compact('customer', 'users', 'groupes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CustomerRequest $request
	 * @param Customer $customer
	 * @return Response
	 */
	public function store(CustomerRequest $request, Customer $customer) {
    	$data = $request->all();
		$customer->fill($data);

		if ($customer->save()){

		    $userCustomer = new UsersCustomer();
		    $userCustomer->user_id = $data['user_id'];
		    $userCustomer->customer_id = $customer->id;
		    $userCustomer->save();

			Session::flash('success', __('customers.created'));
		}else{
            Session::flash('error', __('commun.error'));
            redirect()->route('customers.index');
		}
        return redirect()->route('customers.view', ['customer' => $customer]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Customer  $customer
	 * @return Response
	 */
	public function view(Customer $customer) {
        $orders = $customer->orders()->sortable([
            'date_entered' => 'desc',
        ])
            ->with(['customer', 'groupe'])
            ->paginate(20);
		return view('customers.view', compact('customer', 'orders'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Customer $customer
	 * @return Response
	 */
	public function edit(Customer $customer) {
        $users = User::approvisionneurs()->get()->pluck('libelle', 'id');
        $groupes = Groupe::all()->pluck('name', 'id');
		return view('customers.edit', compact('customer', 'users', 'groupes'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Customer $customer
	 * @param CustomerRequest $request
	 * @return Response
	 */
	public function update(CustomerRequest $request, Customer $customer) {
		$data = $request->all();

		$oldUserId = $customer->user_id;

		if ($customer->update($data)){
		    if($oldUserId != $data['user_id']){
                $userCustomer = new UsersCustomer();
                $userCustomer->user_id = $data['user_id'];
                $userCustomer->customer_id = $customer->id;
                $userCustomer->save();
            }

			Session::flash('success', __('customers.updated'));
            return redirect()->route('customers.view', ['customer' => $customer]);
		}
        Session::flash('error', __('commun.error'));
		return $this->edit($customer);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Customer $customer
	 * @return Response
	 * @throws \Exception
	 */
	public function delete(Customer $customer) {

		if ($customer->delete()) {
			Session::flash('success', __('customers.deleted'));
		} else {
            Session::flash('error', __('commun.error'));
		}
		return redirect()->route('customers.index');
	}

    /**
     * Page to reallocate
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function reallocation(Request $request){
	    $data = $request->all();

        $customers = Customer::sortable()
            ->resetPaginate($request->only(['search']));

        // In case a user is specified
        if(!empty($data['user']) ){
            $customers = $customers->where('user_id', $data['user']);
        }

        $customers = $customers->with(['user'])
            ->orderBy('company_name')
            ->paginate(20);

        $users = User::approvisionneurs()->get()->pluck('libelle', 'id');
        return view('customers.reallocation', compact('customers', 'users'));
    }


    /**
     * Update newly reallocated customer
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storereallocation(Request $request){
        $data = $request->all();

        if(!empty($data['customer']) ){

            $customers = Customer::whereIn('id', $data['customer'])->get();
            foreach($customers as $customer){

                // Modify user_id if different only
                if($customer->user_id != $data['user_id']){
                    $customer->user_id = $data['user_id'];

                    if ($customer->update()) {
                        Session::flash('success', __('customers.reallocation_done'));
                    } else {
                        Session::flash('error', __('customers.error_to_reallocate').' '.$customer->id);
                    }
                }
            }
        } else{
            Session::flash('error', __('customers.need_to_select_customers'));
        }
        return redirect()->route('customers.reallocation');
    }
}
