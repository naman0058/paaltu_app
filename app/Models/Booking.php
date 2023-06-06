<?php

namespace App\Models;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
	protected $table='bookings';
	public $timestamps=false;
	protected $guarded=[];
	public function service()
	{
		return $this->belongsTo(Service::class,'service_id','id');
	}
	public function user()
	{
		return $this->belongsTo(User::class,'pet_user_id','id');
	}
	public function pet_profile()
	{
		return $this->belongsTo(PetProfile::class,'pet_user_id','user_id');
	}
	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->booking_no = IdGenerator::generate(['table' => 'bookings','field'=>'booking_no', 'length' =>10, 'prefix' =>'P'.date('ym')]);
		});
	}
}
