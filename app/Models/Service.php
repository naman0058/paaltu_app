<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $appends = ['icon_url'];
	protected $hidden = ['created_at', 'updated_at','deleted_at','created_by','updated_by'];

    public function getIconUrlAttribute()
    {
        return asset('public/serviceImage') . '/' . $this->icon;
    }
    public function vendor()
    {
		return $this->belongsTo(Vendor::class,'vendor_id','id');
    }
}
