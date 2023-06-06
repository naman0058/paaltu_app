<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Follow;
use Validator;
class ChatController extends BaseController
{
    public function getMembers()
    {
	  $follow=Follow::select('pet_user_id')
	  ->where('user_id',auth()->user()->id)
	  ->where('status','accept')
	  ->get()
	  ->pluck('pet_user_id');
      $members=User::select('id','name','username','dp','user_type')
      ->where('id','!=',auth()->user()->id)
      ->where('user_type','user')
      ->whereIn('id',$follow)
	  ->orWhere('user_type','vendor')
	  ->get();
      return response()->json([
        'result'=>true,
        'members'=>$members
      ]);  
    }
	public function getUserMembers()
	{
		$follow=Follow::select('pet_user_id')
		->where('user_id',auth()->user()->id)
		->where('status','accept')
		->get()
		->pluck('pet_user_id');
		$members=User::select('id','name','username','dp','user_type')
		->where('id','!=',auth()->user()->id)
		->where('user_type','user')
		->whereIn('id',$follow)
		->get();
		return response()->json([
			'result'=>true,
			'members'=>$members
		]); 
	}
	public function getVendorMembers()
	{
		 
		$members=User::select('id','name','username','dp','user_type')
		->where('id','!=',auth()->user()->id)
		->where('user_type','vendor')
		->get();
		return response()->json([
			'result'=>true,
			'members'=>$members
		]); 
	}
    public function getChats()
    {
      $chat=Chat::select('id as chat_id','sender','receiver','updated_at')
      ->with(['sender','recent_message'])
      ->withCount(['unread_messages'])
      ->where('receiver',auth()->user()->id)
	  ->whereHas('sender',function($q){
			$q->where('id','!=',auth()->user()->id);
	  })
      ->get();
      return response()->json([
        'result'=>true,
        'chats'=>$chat
      ]);
    }
	public function getVendorChats()
    {
      $chat=Chat::select('id as chat_id','sender','receiver','updated_at')
      ->with(['sender','recent_message'])
      ->withCount(['unread_messages'])
      ->where('receiver',auth()->user()->id)
	  ->whereHas('sender',function($q){
			$q->where('id','!=',auth()->user()->id)
			->where('user_type','vendor');
	  })
      ->get();
      return response()->json([
        'result'=>true,
        'chats'=>$chat
      ]);
    }
	public function getMessages(Request $request)
    {
        $messages=Message::where(function($q)use($request){
			$q->where('sender',auth()->user()->id)
			->where('receiver',$request->id);
			
		})
		->orWhere(function($q)use($request){
			$q->where('sender',$request->id)
			->where('receiver',auth()->user()->id);
		})
		->orderBy('created_at','desc')
        ->simplePaginate(10);
		$newMessages=[];
		foreach($messages->getCollection() as $message)
		{
			if($message->deleted_for_user != auth()->user()->id && $message->deleted_for_user1 != auth()->user()->id)
			{
				$newMessages[]=$message;
			}
		}
       $sender=User::find($request->id);
       return response()->json([
        'result'=>true,
        'sender'=>$sender,
        'messages'=>$newMessages
      ]); 
    }
     
    public function send(Request $request)
    {
        $chat=Chat::where([
            'sender'=>auth()->user()->id,
            'receiver'=>$request->receiver
        ])->first();
        if(!isset($chat))
        { 
           $chat=new Chat();
           $chat->sender=auth()->user()->id;
           $chat->receiver=$request->receiver;
           $chat->created_at=date('Y-m-d H:i:s');
		   $chat->updated_at=date('Y-m-d H:i:s');
           $chat->save(); 
        }
        //second
       $rchat=Chat::where([
            'receiver'=>auth()->user()->id,
            'sender'=>$request->receiver
        ])->first();
        if(!isset($rchat))
        { 
           $rchat=new Chat();
           $rchat->receiver=auth()->user()->id;
           $rchat->sender=$request->receiver;
           $rchat->created_at=date('Y-m-d H:i:s');
           $rchat->updated_at=date('Y-m-d H:i:s');
           $rchat->save();  
        }
        $message=new Message(); 
        $message->sender=auth()->user()->id;
        $message->receiver=$request->receiver;
        $message->chat_id=$chat->id;
        $message->message_type=$request->message_type;
        $message->message=$request->message;
        $message->created_at=date('Y-m-d H:i:s');
        if($request->has('message_type') && $request->message_type != 'text')
        {
            $file=$request->file('file');
            $name=str_shuffle(time()).time().'.'.$file->extension();
            $path=public_path().'/chat/'.$request->message_type;
            $file->move($path,$name);
            $message->file=$name;
        }
        if($message->save())
        {  
			 
		 
            return response()->json([
                'result'=>true,
                'message'=>$message
            ]);
        }else{
            return response()->json([
                'result'=>false
            ]); 
        }
        
    }
	public function deleteMessage(Request $request)
	{
		$message=Message::where('id',$request->id)->where('sender',auth()->user()->id);
		if(isset($message) && $message->delete())
		{
			 return response()->json([
                'result'=>true
            ]);
		}else{
			 return response()->json([
                'result'=>false
            ]);
		}
	}
	public function deleteChat(Request $request)
	{
		$rchat=Chat::find($request->chat_id);
		if(isset($rchat))
		{
			$chat=Chat::where('sender',auth()->user()->id)
			->where('receiver',$rchat->sender)
			->first();
		 

			$chat_ids=Message::where(function($q){
				$q->where('sender',auth()->user()->id)
				->orWhere('receiver',auth()->user()->id);
			})
			->orWhere(function($q)use($rchat){
				$q->where('sender',$rchat->sender)
				->orWhere('receiver',$rchat->sender);
			})
			->get();
			if( count($chat_ids) != 0)
			{
			
				$chat_ids=$chat_ids->pluck('chat_id')->unique()->toArray();
			}else{
				$chat_ids=[];
			}
			//  return response()->json([
			// 		'result'=>$chat_ids
		    //  ]);
			//delete msg for this chat user
			$messages = Message::whereIn('chat_id', $chat_ids)
			->where(function ($query) {
				$query->whereNull('deleted_for_user')
					->orWhereNull('deleted_for_user1');
			})
			->get();
			 
			foreach($messages as $message)
			{
				if( $message->deleted_for_user == NULL && $message->deleted_for_user1 != auth()->user()->id)
				{
					$deleteMsg=Message::find($message->id);
				    $deleteMsg->deleted_for_user=auth()->user()->id;
				    $deleteMsg->save();
				}elseif($message->deleted_for_user1 == NULL && $message->deleted_for_user != auth()->user()->id ){
					$deleteMsg=Message::find($message->id);
					$deleteMsg->deleted_for_user1=auth()->user()->id;
				    $deleteMsg->save();
				}
			}
			 
			//delete chat
			if($rchat->delete())
			{
				return response()->json([
					'result'=>true
				]);
			} 
		}
		return response()->json([
					'result'=>false
		]);
	}
}