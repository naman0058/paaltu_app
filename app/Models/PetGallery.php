<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PetGallery extends Model
{
	protected $table = 'pet_gallery';
	public $timestamps = false;
	protected $appends=['image_url'];
	public function getImageUrlAttribute()
	{
		return $this->image != '' ? asset('/public/images/pet/files/'.$this->image) : asset('assets/admin/img/paaltu.jpg');
	}
	public static function getImage($id)
	{
		$data=self::where('pet_id', $id)
		->first();
		if($data){
			return $data->image;
		}
		return '';
	}
}