<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Chat extends Model
{
	protected $table = 'chat';
	public $timestamps = false;
	protected $guarded=[];
	protected $appends = ['created_order'];
	 
	public function sender()
	{
	    return $this->hasOne(User::class,'id','sender');
	}
	public function unread_messages()
	{
	    return $this->hasMany(Message::class,'sender','sender')->where('is_read','no');
	}
	public function recent_message()
	{
	    return $this->hasOne(Message::class,'sender','sender')->orderBy('created_at','desc');
	}
	//appends
	 
	public function getCreatedOrderAttribute()
	{
	    return strtotime($this->updated_at);
	}
	 
}