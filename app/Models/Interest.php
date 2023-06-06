<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Interest extends Model
{
protected $table = 'interest_table';
public $timestamps = true;
protected $dates = ['deleted_at'];
}