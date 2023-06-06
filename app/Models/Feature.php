<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Feature extends Model
{
	protected $table = 'features';
	public $timestamps = false;
	public static function getImage($id)
	{
		$data=self::where('service_id', $id)
		->first();
		if($data){
			return $data->image;
		}
		return '';
	}
}