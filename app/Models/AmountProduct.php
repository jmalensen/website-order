<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\AmountProduct
 *
 * @property int $id
 * @property int|null $amount
 * @property float|null $total_price
 * @property int|null $losses
 * @property int|null $order_id
 * @property int|null $product_id
 * @property int|null $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct resetPaginate($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereLosses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmountProduct whereCustomerId($value)
 * @property-read \App\Models\Customer|null $customer
 */
class AmountProduct extends App {
	use Sortable;

	protected $primaryKey = 'id';
	protected $dates = [''];

	public static $rules = [
		'amount' => ['nullable','integer', 'min:0'],
		'order_id' => ['integer', 'min:0'], 
		'product_id' => ['integer', 'min:0'],
		'customer_id' => ['integer', 'min:0'],
        'total_price' => ['numeric', 'min:0'],
        'losses' => ['nullable','integer', 'min:0'],

	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'amount' => 'integer', 
		'order_id' => 'integer', 
		'product_id' => 'integer',
		'customer_id' => 'integer',
        'total_price' => 'double',
        'losses' => 'integer',

	];

	/******************************************************************************************************************
	/*********************************************** RELATIONS ********************************************************
	/*****************************************************************************************************************/

    /**
     * Get order
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(){
        return $this->belongsTo(Order::class);
    }

    /**
     * Get product
     *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(){
        return $this->belongsTo(Product::class);
    }

    /**
     * Get customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(){
        return $this->belongsTo(Customer::class);
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


	protected static function boot() {
		parent::boot(); // TODO: Change the autogenerated stub
		
		static::created(function(AmountProduct $a){
			if (!empty($a->order)) {
				$a->order->calculTotal();
			}
		});
		
		static::updated(function(AmountProduct $a){
			if (!empty($a->order)) {
				$a->order->calculTotal();
			}
		});
	}
}
