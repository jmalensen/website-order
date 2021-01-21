<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\Groupe
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe resetPaginate($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Groupe whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Groupe extends App {
	use Sortable;

	protected $primaryKey = 'id';
	protected $dates = [''];

	public static $rules = [
		'name' => ['string', 'max:255'], 

	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'name' => 'string', 

	];

	/******************************************************************************************************************
	/*********************************************** RELATIONS ********************************************************
	/*****************************************************************************************************************/

    public function users(){
        return $this->hasMany(User::class);
    }

    public function customers(){
        return $this->hasMany(Customer::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
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
				$builder = $builder
				->orWhere('name', 'like', '%'.$term.'%');
			}
			return $builder;
		}

		return $builder;
	}


	/******************************************************************************************************************
	 * /*********************************************** FONCTIONS ********************************************************
	 * /*****************************************************************************************************************/


}
