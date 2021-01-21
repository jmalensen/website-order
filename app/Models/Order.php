<?php

namespace App\Models;

use App\Scopes\GroupeScope;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $date_entered
 * @property \Illuminate\Support\Carbon|null $date_closed
 * @property int|null $user_id
 * @property int|null $customer_id
 * @property string|null $code
 * @property string|null $status
 * @property float|null $total_price
 * @property int|null $groupe_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order resetPaginate($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDateClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDateEntered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AmountProduct[] $amountProducts
 * @property-read int|null $amount_products_count
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGroupeId($value)
 */
class Order extends App {
	use Sortable;

	protected static function boot(){
	    parent::boot();
	    
	    static::creating(function(Order $order) {
	    	if (empty($order->user_id)){
	    		$order->user_id = Auth::user()->id;
			}
		});

        static::addGlobalScope('authenticated', function (Builder $builder) {
            $authUser = Auth::user();
            if( !empty($authUser) && $authUser->isUser() ){
                $builder->where('user_id', '=', Auth::user()->id);
            }
        });

        static::addGlobalScope(new GroupeScope);
    }

    public const STATUS_ACTIVE_ORDER = 'A';
    public const STATUS_IN_PROGRESS_ORDER = 'P';
    public const STATUS_CLOSED_ORDER = 'C';
    public const STATUS_TROUBLE_ORDER = 'T';
    public const STATUS_CANCELED_ORDER = 'N';

	protected $primaryKey = 'id';
	protected $dates = ['date_entered','date_closed'];

	public static $rules = [
        'code' => ['string', 'max:255', 'required'],
	    'status' => ['string', 'min:1', 'max:1'],
		'date_entered' => ['nullable', 'date'],
		'date_closed' => ['nullable', 'date'],
		'user_id' => ['integer', 'min:0', 'required'],
		'customer_id' => ['integer', 'min:0', 'required'],
        'total_price' => ['numeric', 'min:0'],
        'groupe_id' => ['integer', 'min:0', 'required'],

	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
	    'code' => 'string',
	    'status' => 'string',
		'date_entered' => 'datetime',
		'date_closed' => 'datetime', 
		'user_id' => 'integer', 
		'customer_id' => 'integer',
        'total_price' => 'double',
        'groupe_id' => 'integer',
	];

    public $sortable = [
        'date_entered',
        'date_closed',
        'total_price'
    ];

	/******************************************************************************************************************
	/*********************************************** RELATIONS ********************************************************
	/*****************************************************************************************************************/

    /**
     * Get user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Get customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get amount products of order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function amountProducts(){
        return $this->hasMany(AmountProduct::class, 'order_id');
    }

    /**
     * Get groupe
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
				->orWhere('code', 'like', '%'.$term.'%');
			}
			return $builder;
		}

		return $builder;
	}

    /**
     * Only orders with closed status
     * @param Builder $query
     */
	public function scopeClosed(Builder $query) {
		$query->where('status', self::STATUS_CLOSED_ORDER);
	}

    /**
     * Only orders for stats
     * @param Builder $query
     */
	public function scopeStats(Builder $query) {
		$query->whereIn('status', [Order::STATUS_IN_PROGRESS_ORDER, Order::STATUS_CLOSED_ORDER]);
	}

    /**
     * Get orders with specified date
     * @param Builder $query
     * @param $start
     * @param null $end
     */
	public function scopeEntered(Builder $query, $start, $end = null) {
		if (empty($end)){
			$query->whereDate('date_entered', $start);
		} else {
			$query->whereBetween('date_entered', [$start, $end] );
		}
	}


	/******************************************************************************************************************
	 * /*********************************************** FONCTIONS ********************************************************
	 * /*****************************************************************************************************************/

    /**
     * Get status of order
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getStatus(){
        if($this->status == self::STATUS_ACTIVE_ORDER){
            return __('orders.active');
        } else if($this->status == self::STATUS_IN_PROGRESS_ORDER){
            return __('orders.in_progress');
        } else if($this->status == self::STATUS_CLOSED_ORDER){
            return __('orders.closed');
        } else if($this->status == self::STATUS_TROUBLE_ORDER){
            return __('orders.problem');
        } else if($this->status == self::STATUS_CANCELED_ORDER){
            return __('orders.canceled');
        }
    }


    /**
     * Get status of order
     * @param $status
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public static function getStatusLibelle($status){
        if($status == self::STATUS_ACTIVE_ORDER){
            return __('orders.active');
        } else if($status == self::STATUS_IN_PROGRESS_ORDER){
            return __('orders.in_progress');
        } else if($status == self::STATUS_CLOSED_ORDER){
            return __('orders.closed');
        } else if($status == self::STATUS_TROUBLE_ORDER){
            return __('orders.problem');
        } else if($status == self::STATUS_CANCELED_ORDER){
            return __('orders.canceled');
        }
    }


    /**
     * Get status html of order
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getStatusHTML(){
        if($this->status == self::STATUS_ACTIVE_ORDER){
            return '<span class="text-info"><i class="fa fa-clock"></i> '.$this->getStatus().'</span>';

        } else if($this->status == self::STATUS_IN_PROGRESS_ORDER){
            return '<span class="text-warning"><i class="fa fa-spinner"></i> '.$this->getStatus().'</span>';

        } else if($this->status == self::STATUS_CLOSED_ORDER){
            return '<span class="text-success"><i class="fa fa-check"></i> '.$this->getStatus().'</span>';

        } else if($this->status == self::STATUS_TROUBLE_ORDER){
            return '<span class="text-danger"><i class="fa fa-exclamation-circle"></i> '.$this->getStatus().'</span>';

        } else if($this->status == self::STATUS_CANCELED_ORDER){
            return '<span class="text-secondary"><i class="fa fa-times"></i> '.$this->getStatus().'</span>';

        }
    }


    /**
     * Get amount of specified product for this order
     * @param $productId
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    public function getAmountProductForSpecified($productId){
        return $this->amountProducts()->where('product_id', $productId)->first();
    }
	
	/**
	 * Calcul le total de la commande en fonction des produits
	 */
    public function calculTotal(){
    	$this->total_price = $this->amountProducts->sum('total_price');
    	$this->save();
	}

    /**
     * Can modify order
     * @return bool
     */
	public function canModify(){
        return $this->status != self::STATUS_CLOSED_ORDER
            && $this->status != self::STATUS_CANCELED_ORDER;
    }

    /**
     * Can pause order
     * @return bool
     */
    public function canPause(){
        return $this->status != self::STATUS_CLOSED_ORDER
            && $this->status != self::STATUS_TROUBLE_ORDER
            && $this->status != self::STATUS_CANCELED_ORDER;
    }

    /**
     * Can modify order for member
     * @return bool
     */
    public function canModifyMember(){
        return $this->status == self::STATUS_ACTIVE_ORDER;
    }

    /**
     * Can duplicate order for member
     * @return bool
     */
    public function canDuplicateMember(){

        $canDoOrder = now()->format('H:i:s') < config('app.lastTimeToOrder');

        return $this->status == self::STATUS_CLOSED_ORDER
            && now()->diffInDays($this->date_closed) <= config('app.daysForDuplication')
            && $canDoOrder;
    }

    /**
     * Can input losses on order
     * @return bool
     */
    public function canPutLosses(){

        if(empty($this->date_closed) || $this->status != self::STATUS_CLOSED_ORDER){
            return false;
        }

        $diffDays = now()->diffInDays($this->date_closed);

        return $diffDays <= config('app.daysToNotifyLosses');
    }

    /**
     * Can view losses on products of order
     * @return bool
     */
    public function canViewLosses(){
        return $this->status == self::STATUS_CLOSED_ORDER;
    }
}
