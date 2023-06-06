<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PetProfile extends Model
{
    protected $table = 'pet_profile';
    public $timestamps = true;
    protected $dates = ['deleted_at'];
	protected $guarded=[];
	//append attributes
	// protected $appends=['breed_name','category_name','category_description'];
	// public function getBreedNameAttribute()
	// {
	// 	return $this->breed->breed_name;
	// }
	// public function getCategoryNameAttribute()
	// {
	// 	return $this->category->name;
	// }
	// public function getCategoryDescriptionAttribute()
	// {
	// 	return $this->category->description;
	// }
	//relations
    public function category()
    {
        return $this->hasOne(PetCategory::class, 'id', 'pet_category_id');
    }
    public function breed()
    {
        return $this->hasOne(Breed::class, 'id', 'breed_id');
    }
    public function petItems()
    {
        return $this->hasMany(PetGallery::class, 'pet_id', 'id')->orderBy('is_default', 'asc');
    }
    public function photos()
    {
        return $this->hasMany(PetGallery::class, 'pet_id', 'id')->orderBy('is_default', 'asc');
    }
	public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id','name','username');
    }
	public static function getNameByUserId($id)
	{
		$data=Self::where('user_id',$id)->first();
		if(isset($data))
		{
			return $data->pet_name;
		}
		return '';
	}
}
