<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\Customer;
use App\Models\Groupe;
use App\Models\UsersCustomer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller {
	
	public function index(Request $request){
		$users = User::with(['roles', 'groupe'])->sortable()
			->resetPaginate($request->only(['search']))
			->orderBy('firstname')
			->paginate(20);
		return view('users.index', compact('users'));
	}
	/**
	 * Formulaire Création d'un nouvel utilisateur
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create() {
		$user = new User();
        $groupes = Groupe::all()->pluck('name', 'id');
        return view('users.create', compact('user', 'groupes'));
    }
	
	/**
	 * Formulaire Modification d'un utilisateur existant
	 * @param User $user
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit(User $user) {
        $groupes = Groupe::all()->pluck('name', 'id');
        return view('users.edit', compact('user', 'groupes'));
    }

    /**
     * Vue d'un utilisateur
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(User $user) {
        $orders = $user->orders()->sortable([
            'date_entered' => 'desc',
        ])
            ->with(['customer', 'groupe'])
            ->paginate(20);

        $customers = Customer::with(['user', 'groupe'])
            ->orderBy('company_name')
            ->paginate(20);

        return view('users.view', compact('user', 'orders', 'customers'));
    }
	
	/**
	 * Formulaire de changement de mot de passe
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function changepassword(){
		return view('users.changepassword');
	}
	
	/**
	 * Enregistrement de la modification de mot de passe
	 * si ok il déconnecte l'utilisateur courant
	 * sinon message d'erreur
	 * @param ChangePasswordRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function updatepassword(ChangePasswordRequest $request){
 		$user = auth()->user();
 		if (Hash::check($request->old_password,$user->password)){
 			$user->password = bcrypt($request->password);
 			$user->password_change_at = Carbon::now();
 			if ($user->save()) {
 				auth()->logout();
 				return redirect()->route('login');
			}
 			Session::flash('error', __('users.notupdated'));
		} else {
 			Session::flash('error', __('users.wrong old password'));
		}
		return redirect()->route('users.changepassword');
	}
	
	/**
	 * Suppresion de l'utilisateur $user
	 * @param User $user
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
    public function delete(User $user){

        // In case, this is a user_appro and has customer on charge
        if($user->isUser() && $user->customers()->count() > 0){

            Session::flash('error', __('users.customers_of_user_need_reallocation'));
            return redirect()->route('customers.reallocation', ['user' => $user->id]);
        }

        // Remove all relations with customers
        $user->userscustomers()->delete();

		if ($user->delete()) {
			Session::flash('success', __('users.deleted'));
		} else {
			Session::flash('error', __('users.notdeleted'));
		}
		return redirect()->route('users.index');
	}
	
	/**
	 * Enregistrement d'un nouvel utilisateur
	 * @param UserRequest $request
	 * @param User $user
	 * @return \Illuminate\Http\RedirectResponse
	 */
    public function store(UserRequest $request,User $user) {
		if ($user->newOne($request->all())){
			Session::flash('success', __('users.created'));
		}else{
			Session::flash('error', __('users.notcreated'));
		}
        return redirect()->route('users.view', [$user]);
    }
	
	/**
	 * Enregistrement des modifications sur un utilisateur
	 * @param UserRequest $request
	 * @param User $user
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function update(UserRequest $request, User $user){
		$data = $request->all();
		if($user->editUserAndRoles($data)){
			Session::flash('success', __('users.updated'));
            return redirect()->route('users.view', [$user]);
		}
		Session::flash('error', __('users.notupdated'));
		return $this->edit($user);
	}

    /**
     * Allocate user on customers
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function allocate(Request $request, User $user) {
        $data = $request->all();

        $customers = Customer::whereIn('id', array_keys($data['affect']))->get();
        foreach($customers as $customer){

            $userCustomer = UsersCustomer::where('user_id', $user->id)
                ->where('customer_id', $customer->id)
                ->first();

            // New allocation
            if($data['affect'][$customer->id] && empty($userCustomer) ){
                $userCustomer = new UsersCustomer();
                $userCustomer->user_id = $user->id;
                $userCustomer->customer_id = $customer->id;
                $userCustomer->save();
            } elseif(!$data['affect'][$customer->id] && !empty($userCustomer) ){
                $userCustomer->delete();
            }
        }

        return redirect()->route('users.view', [$user]);
    }
	
}
