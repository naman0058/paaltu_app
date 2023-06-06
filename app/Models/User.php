<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'mobile',
		'dp',
        'address',
    	'alt_mobile'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
		'is_profile_added'=>'boolean'
    ];
    protected $appends = ['dp_url','vendor_dp_url'];
	protected $dates = ['deleted_at'];
	public function getDpUrlAttribute()
    {
        $path=asset('/public/users');
		if($this->dp == '')
		{
			return 'https://ui-avatars.com/api/?name='.$this->name.'&background=random';
		}
        return $path.'/'.$this->dp;
    }
	public function getVendorDpUrlAttribute()
    {
        $path=asset('/public/uploads/vendor/');
		if($this->dp == '')
		{
			return 'https://ui-avatars.com/api/?name='.$this->name.'&background=random';
		}
        return $path.'/'.$this->dp;
    }
	public static function getName($id)
	{
		$data=Self::find($id);
		if(isset($data))
		{
			return ucfirst($data->name);
		}
		return '';
	}
}
