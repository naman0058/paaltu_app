<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
class Message extends Model
{
    use SoftDeletes;
	protected $table = 'chat_messages';
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	protected $appends = ['file_url'];
	public function getFileUrlAttribute()
    {
        $path=asset('/public/chat').'/'.$this->message_type;
        return $path.'/'.$this->file;
    }
    public function user()
	{
	    return $this->hasOne(User::class,'id','sender');
	}
}