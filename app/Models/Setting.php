<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Setting extends Model
{
	protected $table='setting';
	public $timestamps=false;
	public static function getValue($label)
	{
		$data = self::where('label',$label)->first();
		if($data)
		{
			return $data->value;
		}
	}
}