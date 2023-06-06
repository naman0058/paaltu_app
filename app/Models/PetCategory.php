<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PetCategory extends Model
{
protected $table = 'pet_category';
public $timestamps = true;
protected $dates = ['deleted_at'];
}