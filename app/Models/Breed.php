<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Breed extends Model
{
	protected $table = 'breed';
	public $timestamps = true;
	protected $dates = ['deleted_at'];
	public function pet_category()
	{
		return $this->hasOne(PetCategory::class,'id','pet_category_id');
	}
}