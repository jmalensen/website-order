<?php

namespace App\Policies;
use App\Models\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy {
	use HandlesAuthorization;
	
	public function createAdmin(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){

            return true; //todo: vérifier s'il y a des clients ?
        }
        return false;
	}


	public function editAdmin(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            if( $order->canModify() ){
                return true;
            }
        }
        return false;
	}
	

	/**
	* si $order est null; on est dans la vue index
	*/
	public function viewAdmin(User $connecteduser, Order $order = null) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            return true;
        }
        return false;
	}
	
	
	public function deleteAdmin(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
	}


    public function closeAdmin(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            if( $order->canModify() ){
                return true;
            }
        }
        return false;
    }

    public function pauseAdmin(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            if( $order->canPause() ){
                return true;
            }
        }
        return false;
    }

    public function cancelAdmin(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            if( $order->canModify() ){
                return true;
            }
        }
        return false;
    }

    public function duplicateAdmin(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient()){
            if(!empty($order->date_closed)
                && $order->status == Order::STATUS_CLOSED_ORDER ){

                $date3DaysBefore = Carbon::now()->subDays(3);
                $dateNow = Carbon::now();
                if($order->date_closed->between($date3DaysBefore, $dateNow)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function massModifyAdmin(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() ){
            return true;
        }
        return false;
    }



    public function createMember(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient() || $connecteduser->isUser() ){

            return true; //todo: vérifier s'il y a des clients ?
        }
        return false;
    }


    public function editMember(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient() || $connecteduser->isUser() ){
            if( $order->canModifyMember() ){
                return true;
            }
        }
        return false;
    }


    /**
     * si $order est null; on est dans la vue index
     */
    public function viewMember(User $connecteduser, Order $order = null) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient() || $connecteduser->isUser() ){
            return true;
        }
        return false;
    }


    public function duplicateMember(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $connecteduser->isAdmin() || $connecteduser->isAdminClient() || $connecteduser->isUser() ){

            if(!empty($order->date_closed)
                && $order->status == Order::STATUS_CLOSED_ORDER
                && $order->canDuplicateMember() ){
                return true;
            }
        }
        return false;
    }


    public function deleteMember(User $connecteduser, Order $order) {
        if( $connecteduser->isSuperAdmin() || $order->status == Order::STATUS_ACTIVE_ORDER ){
            return true;
        }
        return false;
    }
}
