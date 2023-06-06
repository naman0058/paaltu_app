<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Blog extends Model
{
protected $table = 'blog';
public $timestamps = true;
protected $dates = ['deleted_at'];
}