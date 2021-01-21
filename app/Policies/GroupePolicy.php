<?php

namespace App\Policies;
use App\Models\Groupe;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupePolicy {
	use HandlesAuthorization;
	
	public function create(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
	
	public function edit(User $connecteduser, Groupe $groupe) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
	

	/**
	* si $groupe est null; on est dans la vue index
	*/
	public function view(User $connecteduser, Groupe $groupe = null) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
	
	
	public function delete(User $connecteduser, Groupe $groupe) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}
}
