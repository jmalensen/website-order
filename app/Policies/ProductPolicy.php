<?php

namespace App\Policies;
use App\Models\Product;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy {
	use HandlesAuthorization;
	
	public function create(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
	
	public function edit(User $connecteduser, Product $product) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
	

	/**
	* si $product est null; on est dans la vue index
	*/
	public function view(User $connecteduser, Product $product = null) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            return true;
        }
        return false;
	}
	
	
	public function delete(User $connecteduser, Product $product) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
}
