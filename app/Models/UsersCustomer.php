<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\UsersCustomer
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer resetPaginate($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsersCustomer whereUserId($value)
 * @mixin \Eloquent
 */
class UsersCustomer extends App {
	use Sortable;

	protected $primaryKey = 'id';
	protected $dates = [''];

	public static $rules = [
		'user_id' => ['integer', 'min:0'], 
		'customer_id' => ['integer', 'min:0'], 

	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'user_id' => 'integer', 
		'customer_id' => 'integer', 

	];

	/******************************************************************************************************************
	/*********************************************** RELATIONS ********************************************************
	/*****************************************************************************************************************/

    /**
     * Get customers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function customers(){
	    return $this->hasMany(Customer::class);
    }

    /**
     * Get users
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(){
	    return $this->hasMany(User::class);
    }


	/******************************************************************************************************************
	/*********************************************** SCOPES ***********************************************************
	/*****************************************************************************************************************/

	/**
	 * @param Builder $builder
	 * @param $data
	 * @return Builder
	 */
	public function scopeResetPaginate(Builder $builder, $data){
		if (!empty($data['search'])){
			$terms = explode(' ', $data['search']);
			foreach($terms as $term){
				/*$builder = $builder
				->orWhere('name', 'like', '%'.$term.'%');*/
			}
			return $builder;
		}

		return $builder;
	}


	/******************************************************************************************************************
	 * /*********************************************** FONCTIONS ********************************************************
	 * /*****************************************************************************************************************/


}
