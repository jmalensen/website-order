<?php

namespace App\Models;

use App\Scopes\GroupeScope;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $description
 * @property float|null $price_ht
 * @property float|null $rate_vat
 * @property int|null $groupe_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product resetPaginate($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\AmountProduct $amountProduct
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePriceHt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereRateVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereGroupeId($value)
 */
class Product extends App {
	use Sortable;

    // Add GroupeScope for current Product
    protected static function boot() {
        parent::boot();

        static::addGlobalScope(new GroupeScope);
    }

	protected $primaryKey = 'id';
	protected $dates = [''];

	public static $rules = [
		'code' => ['string', 'max:255', 'required'],
		'name' => ['string', 'max:255', 'required'],
		'description' => ['nullable', 'string', 'max:65535'],
		'price_ht' => ['numeric', 'required'],
		'rate_vat' => ['numeric', 'required'],
        'groupe_id' => ['integer', 'min:0', 'required'],
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'code' => 'string', 
		'name' => 'string', 
		'description' => 'string', 
		'price_ht' => 'double',
		'rate_vat' => 'double(10.2)',
        'groupe_id' => 'integer',

	];

    public $sortable = [
        'code',
        'name',
        'price_ht'
    ];

    /******************************************************************************************************************
	/*********************************************** RELATIONS ********************************************************
	/*****************************************************************************************************************/

    /**
     * Get amount product
     *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function amountProducts(){
        return $this->hasMany(AmountProduct::class);
    }
    
    public function cloturedAmountProducts(){
    	return $this->amountProducts()->whereHas('order', function($q){
			$q->closed();
		});
	}

    public function groupe(){
        return $this->belongsTo(Groupe::class);
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
				->orWhere('code', 'like', '%'.$term.'%')
				->orWhere('name', 'like', '%'.$term.'%')
				->orWhere('description', 'like', '%'.$term.'%');
			}
			return $builder;
		}

		return $builder;
	}


	/******************************************************************************************************************
	 * /*********************************************** FONCTIONS ********************************************************
	 * /*****************************************************************************************************************/

    /**
     * Show product info
     * @return string
     */
    public function showProductInfo($showPrice = true){
        if($showPrice){
            return '(code: '.$this->code.') '. $this->name.' - '.$this->price_ht.'€';
        } else{
            return '(code: '.$this->code.') '. $this->name;
        }
    }


    /**
     * Get ProductsWithOrder
     * @param string $day
     * @param string|null $dayEnd
     * @return AmountProduct[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function getProductsWithOrder($day, $dayEnd = null){
        $products = AmountProduct::whereHas('order', function($q) use($day, $dayEnd) {
            $q->Stats()->Entered($day, $dayEnd);
        })->groupBy(['product_id'])
            ->with(['product'])
            ->select('product_id',
                DB::raw('sum(amount) as countProduct'),
                DB::raw('sum(total_price) as total_price'),
                DB::raw('sum(losses) as countLossesProduct'))
            ->get()
            ->groupBy('product_id');

        return $products;
    }


    /**
     * Get ProductsWithOrderWithoutDate
     * @return AmountProduct[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function getProductsWithOrderWithoutDate(){
        $products = AmountProduct::whereHas('order', function($q) {
            $q->Stats();
        })->groupBy(['product_id'])
            ->with(['product'])
            ->select('product_id',
                DB::raw('sum(amount) as countProduct'),
                DB::raw('sum(total_price) as total_price'),
                DB::raw('sum(losses) as countLossesProduct'))
            ->get()
            ->groupBy('product_id');

        return $products;
    }


    /**
     * Export product data
     * @param string|null $day
     * @param string|null $dayEnd
     * @return array
     */
    public static function exportProductData($day = null, $dayEnd = null){

        if($day && $dayEnd) {
            // Ordered products
            $products = self::getProductsWithOrder($day, $dayEnd.' 23:59:59');

            $dateTitle = $day.' au '.$dayEnd;

        } else if($day){
            // Ordered products
            $products = self::getProductsWithOrder($day);

            $dateTitle = $day;

        } else{
            // Ordered products
            $products = self::getProductsWithOrderWithoutDate();

            $dateTitle = 'depuis le début';
        }

        $data = [
            'title' => __('commun.stats_byproduct'),
            'heading' => __('commun.stats_byproduct'),
            'productWithOrder' => $products,
            'dateTitle' => $dateTitle,
        ];

        return $data;
    }
}
