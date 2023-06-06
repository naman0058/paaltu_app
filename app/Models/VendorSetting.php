<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class VendorSetting extends Model
{
	protected $table='vendor_setting';
	public $timestamps=false;
	public static function getValue($label,$vendor_id)
	{
		$data = self::where('label',$label)->where('vendor_id',$vendor_id)->first();
		if($data)
		{
			return $data->value;
		}
	}
}