<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use \Hash;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $timestamps = true;
    protected $dates = [
        'deleted_at',
    ];

    public static $user_types = [
        'admin' => [
            'code' => 'admin',
            'name' => 'Admin',
        ],
        'default' => [
            'code' => 'default',
            'name' => 'Default',
        ],
    ];

    public function info()
    {
        return $this->hasOne('App\UserInfo', 'user_id');
    }

    public function scopeExcludeAdmins($query)
    {
        return $query->where('user_type', '<>', self::$user_types['admin']['code']);
    }

    public function scopeExcludeDeleted($query)
    {
        return $query->where('deleted_at', null);
    }

    public function getFullNameAttribute()
    {
        return "{$this->info->firstname} {$this->info->lastname}";
    }

    public function getTypeAttribute()
    {
        return self::$user_types[$this->user_type];
    }

    public function setPasswordAttribute($password_value)
    {
        $this->attributes['password'] = Hash::make($password_value);
    }

    public function removeInfo()
    {
        $this->info()->delete();
    }
}
