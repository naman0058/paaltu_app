<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorService extends Model
{
    use HasFactory,SoftDeletes;
	protected $table='vendor_services';
    protected $dates = ['deleted_at'];
	protected $hidden = ['created_at', 'updated_at','deleted_at','created_by','updated_by'];
	public function service()
	{
		return $this->belongsTo(Service::class,'service_id','id');
	}
	public function vendor()
	{
		return $this->belongsTo(Vendor::class,'vendor_id','id');
	}

}
