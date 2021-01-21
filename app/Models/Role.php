<?php

namespace App\Models;
use Auth;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role superAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role admin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role user()
 */
class Role extends App {
	public static $ID_SUPERADMIN = 1;
	public static $ID_ADMIN = 2;
	public static $ID_USER = 3;
	public static $ID_ADMINCLIENT = 4;

	public static function scopeSuperAdmin(Builder $builder) {
		return $builder
			->whereIN('id', [self::$ID_SUPERADMIN]);
	}

	public static function scopeAdmin(Builder $builder) {
		return $builder
			->whereIN('id', [self::$ID_ADMIN]);
	}

    public static function scopeUser(Builder $builder) {
        return $builder
            ->whereIN('id', [self::$ID_USER]);
    }

    public static function scopeAdminClient(Builder $builder) {
        return $builder
            ->whereIN('id', [self::$ID_ADMINCLIENT]);
    }
	
	public static function getAll(){
		if (Auth::user()->isSuperAdmin()){
			return self::pluck('name', 'id');
		} elseif(Auth::user()->isAdmin()) {
			return self::where('id', '!=', self::$ID_SUPERADMIN)->pluck('name', 'id');
		} elseif(Auth::user()->isAdminClient()){
            return self::where('id', '=', self::$ID_USER)->pluck('name', 'id');
        }
	}

}
