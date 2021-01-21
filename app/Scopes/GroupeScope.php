<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class GroupeScope implements Scope {

    public function apply(Builder $builder, Model $model) {
        if(Auth::hasUser()
            && Auth::user()
            && (Auth::user()->isAdminClient() || Auth::user()->isUser() ) ){
            $builder->where('groupe_id', Auth::user()->groupe_id);
        }
    }
}