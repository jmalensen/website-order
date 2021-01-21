<?php

namespace App\Policies;
use App\Models\AmountProduct;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AmountProductPolicy {
	use HandlesAuthorization;
	
	public function create(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
	
	public function edit(User $connecteduser, AmountProduct $amount_product) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
	

	/**
	* si $amount_product est null; on est dans la vue index
	*/
	public function view(User $connecteduser, AmountProduct $amount_product = null) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
	
	
	public function delete(User $connecteduser, AmountProduct $amount_product) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
}
