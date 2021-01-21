<?php

namespace App\Models;

use App\Scopes\GroupeScope;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string|null $company_name
 * @property string|null $address
 * @property string|null $address_complement
 * @property string|null $city
 * @property string|null $postcode
 * @property string|null $country
 * @property string|null $phone
 * @property int|null $user_id
 * @property int|null $groupe_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer resetPaginate($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereAddressComplement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereGroupeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AmountProduct[] $amountProducts
 * @property-read int|null $amount_products_count
 */
class Customer extends App {
	use Sortable;

    // Add GroupeScope for current Customer
    protected static function boot() {
        parent::boot();

        static::addGlobalScope(new GroupeScope);
    }

	protected $primaryKey = 'id';
	protected $dates = [''];

	public static $rules = [
		'company_name' => ['string', 'max:255', 'required'],
		'address' => ['string', 'max:255', 'required'],
		'address_complement' => ['nullable', 'string', 'max:255'],
		'city' => ['string', 'max:255', 'required'],
		'postcode' => ['string', 'max:255', 'required'],
		'country' => ['nullable', 'string', 'max:255'],
		'phone' => ['string', 'max:255', 'required'],
		'user_id' => ['integer', 'min:0', 'required'],
		'groupe_id' => ['integer', 'min:0', 'required'],

	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'company_name' => 'string', 
		'address' => 'string', 
		'address_complement' => 'string', 
		'city' => 'string', 
		'postcode' => 'string', 
		'country' => 'string', 
		'phone' => 'string', 
		'user_id' => 'integer',
		'groupe_id' => 'integer',

	];

	public static $countries = [
	    'FRANCE' => 'France',
	    'LUXEMBOURG' => 'Luxembourg',
	    'BELGIQUE' => 'Belgique'
    ];

    public $sortable = [
        'company_name'
    ];

    public function getLibelleWithUser() {
        return mb_strtoupper($this->company_name). ' - ' .data_get($this, 'user.libelle');
    }

    public function getLibelleWithUserAttribute() {
        return $this->getLibelleWithUser();
    }


	/******************************************************************************************************************
	/*********************************************** RELATIONS ********************************************************
	/*****************************************************************************************************************/

    /**
     * Get orders of customer
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(){
        return $this->hasMany(Order::class);
    }


    public function amountProducts(){
        return $this->hasMany(AmountProduct::class);
    }


    /**
     * Get user ref of customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Get users of customer
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function users(){
        return $this->hasManyThrough(User::class, UsersCustomer::class, 'customer_id', 'id', 'id', 'user_id');
    }

    /**
     * Get groupe of customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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
				->orWhere('company_name', 'like', '%'.$term.'%');
			}
			return $builder;
		}

		return $builder;
	}


    /**
     * @return mixed
     */
	public function scopeCustomerWithOrder(){

        $queryCustomer = Order::Stats()
            ->groupBy('customer_id')
            ->select('customer_id', DB::raw('count(*) as countOrder'), DB::raw('sum(total_price) as total_price'))
            ->with(['customer']);

        return $queryCustomer;
    }


	/******************************************************************************************************************
	 * /*********************************************** FONCTIONS ********************************************************
	 * /*****************************************************************************************************************/

    public static function getAll(){
        return self::all()->pluck('company_name', 'id');
    }

    /**
     * Get total price of all orders for customer
     * @param boolean $currentDay
     * @return mixed
     */
    public function getTotalPriceOrders($currentDay = false){
        if($currentDay){
            $beginDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $endDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            return $this->orders()->whereBetween('date_entered', [$beginDay, $endDay] )->sum(('total_price'));
        } else{
            return $this->orders()->sum(('total_price'));
        }
    }


    /**
     * Get count orders for customer
     * @param bool $currentDay
     * @return int|string
     */
    public function getCountOrders($currentDay = false){
        if($currentDay){
            $beginDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $endDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            return $this->orders()
                ->whereBetween('date_entered', [$beginDay, $endDay] )
                ->whereIn('status', [Order::STATUS_IN_PROGRESS_ORDER, Order::STATUS_CLOSED_ORDER])
                ->count();
        } else {
            return (!empty($this->orders)) ? $this->orders()->whereIn('status', [Order::STATUS_IN_PROGRESS_ORDER, Order::STATUS_CLOSED_ORDER])->count() : '';
        }
    }


    /**
     * Get stats products
     * @param bool $currentDay
     * @return array
     */
    public function getTotalProducts($currentDay = false){

        if($currentDay) {
            $beginDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $endDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            // Only orders in progress or done of current day
            $amountProducts = $this->orders()
                ->whereIn('status', [Order::STATUS_IN_PROGRESS_ORDER, Order::STATUS_CLOSED_ORDER])
                ->whereBetween('date_entered', [$beginDay, $endDay])
                ->join('amount_products', 'orders.id', 'amount_products.order_id')
                ->get();
        } else{
            // Only orders in progress or done
            $amountProducts = $this->orders()
                ->whereIn('status', [Order::STATUS_IN_PROGRESS_ORDER, Order::STATUS_CLOSED_ORDER])
                ->join('amount_products', 'orders.id', 'amount_products.order_id')
                ->get();
        }

        // Prepare table of products
        $products = Product::all()->pluck('code', 'id')->toArray();
        foreach ($products as $key => $code){
            $product = Product::find($key);
            $products[$key] = [
                'amount' => 0,
                'total_price' => 0,
                'product' => $product,
            ];
        }

        // AmountProducts
        foreach ($amountProducts as $amountProduct){
            $products[$amountProduct->product_id]['amount'] += $amountProduct->amount;
            $products[$amountProduct->product_id]['total_price'] += $amountProduct->total_price;
        }

        return $products;
    }


    /**
     * Get collection of customers with a least one order
     * @return Customer[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getCustomerWithOrders(){
        $customers = self::all();
        // Take only customers with orders
        foreach ($customers as $key => $customer){
            if($customer->getCountOrders() <= 0 ){
                $customers->forget($key);
            }
        }

        return $customers;
    }


    /**
     * Export customer data
     * @param string|null $day
     * @param string|null $dayEnd
     * @return array
     */
    public static function exportCustomerData($day = null, $dayEnd = null){

        $queryCustomer = Order::Stats()
            ->groupBy('customer_id')
            ->select('customer_id', DB::raw('count(*) as countOrder'), DB::raw('sum(total_price) as total_price'))
            ->with(['customer']);
        if($day && $dayEnd){
            $customers = $queryCustomer->Entered($day, $dayEnd.' 23:59:00')->get();
            $dateTitle = $day.' au '.$dayEnd;
        } else if($day){
            $customers = $queryCustomer->Entered($day)->get();
            $dateTitle = $day;
        } else{
            $customers = $queryCustomer->get();
            $dateTitle = 'depuis le début';
        }

        $data = [
            'title' => __('commun.stats_bycustomer'),
            'heading' => __('commun.stats_bycustomer'),
            'customers' => $customers,
            'dateTitle' => $dateTitle,
        ];

        return $data;
    }


    /**
     * Get ProductsByCustomer
     * @param string $day
     * @param string|null $dayEnd
     * @return AmountProduct[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function getProductsByCustomer($day, $dayEnd = null){
        $productByCustomer = AmountProduct::whereHas('order', function($q) use($day, $dayEnd) {
            $q->Stats()->Entered($day, $dayEnd);
        })->groupBy(['customer_id', 'product_id'])
            ->with(['product', 'customer'])
            ->select('product_id', 'customer_id',
                DB::raw('sum(amount) as countProduct'),
                DB::raw('sum(total_price) as total_price'),
                DB::raw('sum(losses) as countLossesProduct'))
            ->get()
            ->groupBy('customer_id');

        return $productByCustomer;
    }


    /**
     * Get ProductsByCustomerWithoutDate
     * @return AmountProduct[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function getProductsByCustomerWithoutDate(){
        $productByCustomer = AmountProduct::whereHas('order', function($q) {
            $q->Stats();
        })->groupBy(['customer_id', 'product_id'])
            ->with(['product', 'customer'])
            ->select('product_id', 'customer_id',
                DB::raw('sum(amount) as countProduct'),
                DB::raw('sum(total_price) as total_price'),
                DB::raw('sum(losses) as countLossesProduct'))
            ->get()
            ->groupBy('customer_id');

        return $productByCustomer;
    }


    /**
     * Export product by customer data
     * @param string|null $day
     * @param string|null $dayEnd
     * @return array
     */
    public static function exportProductByCustomerData($day = null, $dayEnd = null){

        if($day && $dayEnd){
            $productByCustomer = self::getProductsByCustomer($day, $dayEnd.' 23:59:00');
            $dateTitle = $day.' au '.$dayEnd;

        } else if($day){
            $productByCustomer = self::getProductsByCustomer($day);
            $dateTitle = $day;

        } else{
            $productByCustomer = self::getProductsByCustomerWithoutDate();
            $dateTitle = 'depuis le début';
        }

        $data = [
            'title' => __('commun.stats_productsbycustomer'),
            'heading' => __('commun.stats_productsbycustomer'),
            'productByCustomer' => $productByCustomer,
            'dateTitle' => $dateTitle,
        ];

        return $data;
    }
}
