<?php

namespace App\Policies;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy {
	use HandlesAuthorization;
	
	public function create(User $connecteduser) {
		if ($connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()) {
			return true;
		}
		return false;
	}
	
	public function edit(User $connecteduser, User $user) {
		if ($connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()) {
			return true;
		}
		return false;
	}
	
	
	public function view(User $connecteduser, User $user) {
        if ($connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()) {
            return true;
        }
        return false;
	}
	
	
	public function delete(User $connecteduser, User $user) {
		if ($connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()) {
			return true;
		}
		return false;
	}


    public function seeAdminSettings(User $connecteduser){
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin()){
            return true;
        }
        return false;
    }
}
