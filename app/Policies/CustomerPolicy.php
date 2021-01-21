<?php

namespace App\Policies;
use App\Models\Customer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy {
	use HandlesAuthorization;
	
	public function create(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            return true;
        }
        return false;
	}
	
	public function edit(User $connecteduser, Customer $customer) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            return true;
        }
        return false;
	}
	

	/**
	* si $customer est null; on est dans la vue index
	*/
	public function view(User $connecteduser, Customer $customer = null) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            return true;
        }
        return false;
	}
	
	
	public function delete(User $connecteduser, Customer $customer) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            return true;
        }
        return false;
	}


    public function reallocation(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            return true;
        }
        return false;
    }
}
