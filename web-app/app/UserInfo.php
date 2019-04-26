<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model
{
    use SoftDeletes;

	protected $fillable = [
		'user_id',
		'firstname',
		'lastname',
		'address',
		'postcode',
		'phone_number',
	];
}
