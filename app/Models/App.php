<?php

namespace App\Models;

use App\Traits\SaveToUpper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\App
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\App whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\App whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\App whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\App whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\App onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\App withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\App withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App query()
 */
class App extends Model {

	use SoftDeletes;
	use SaveToUpper;

    protected $guarded = [];
	public $useUpperCase = true;
	public $UseUpperCaseAttributes = ['name', 'firstname', 'address', 'city', 'country', 'zipcode', 'title'];
	public static $rules = [];
 
	
	public static function formatPhoneNumber($value){
		return preg_replace('/[^0-9\+]/', '', $value);
	}
	
	public static function formatPrice($value, $decimals = 2, $suffixe='€'){
		return number_format($value, $decimals, ',', ' ').$suffixe;
	}
	public function getRules(){
		return static::$rules;
	}
	public function isRequiredAttribute($attribute){
		$rules = $this->getRules();
		if (empty($rules) || empty($rules[$attribute])){
			return false;
		}
		return in_array('required', $rules[$attribute]);
	}
}
