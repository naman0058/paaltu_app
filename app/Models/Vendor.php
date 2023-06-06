<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory,SoftDeletes;
	protected $table='vendors';
	public $timestamps=true;
	protected $guarded=[];
	protected $dates = ['deleted_at'];
	protected $appends = ['icon_url'];
	public function getIconUrlAttribute()
    {
        $path=asset('/public/uploads/vendor');
		if($this->icon == '')
		{
			return 'https://ui-avatars.com/api/?name='.$this->name.'&background=random';
		}
        return $path.'/'.$this->icon;
    }
	// public function service()
	// {
	// 	return $this->hasMany(Service::class,'vendor_id','id');
	// }
	// public function vendor_services()
	// {
	// 	return $this->hasMany(VendorService::class,'vendor_id','id');
	// }
	public function vendor_services()
	{
		return $this->hasManyThrough(
            Service::class, 
            VendorService::class,
            'vendor_id', // Foreign key on VendorService table
            'id', // Foreign key on Service table
            'id', // Local key on Vendor table
            'service_id' // Local key on VendorService table
        );
	}
	public function vendor_service()
	{
		return $this->hasMany(
            VendorService::class,
            'vendor_id', 
            'id', //  
        );
	}
	public function user()
	{
		return $this->belongsTo(User::class,'user_id','id');
	}
	public static function getVendorId()
	{
		$data=Self::where('user_id',auth()->user()->id)->first();
		return $data->id;
	}
	public static function getVendorName($id)
	{
		$service=Service::find($id);
		if(isset($service))
		{
			$data=Self::find($service->vendor_id);
			if(isset($data))
			{
				return $data->name;
			}
		}
		return '';
	}
}
