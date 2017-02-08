<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ChatUserLists extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    
    protected $table = 'chat_user_list';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'recruiterId' => 'recruiter_id',
        'seekerId' => 'seeker_id',
        'recruiterBlock' => 'recruiter_block',
        'seekerBlock' => 'seeker_block',
        'chatActive' => 'chat_active',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        ];  
   
    public static function getRecruiterListForChat($userId){
        return static::join('recruiter_profiles','recruiter_profiles.user_id','=','chat_user_list.recruiter_id')
            ->join('user_chat',function($query){
                $query->on('user_chat.to_id','=','chat_user_list.recruiter_id')
                    ->orOn('user_chat.from_id','=','chat_user_list.recruiter_id');
            })
            ->where(function($query) use ($userId){
                return $query->where('user_chat.from_id',$userId)
                    ->orwhere('user_chat.to_id',$userId);
            })
            ->where('chat_user_list.seeker_id',$userId)
            ->groupBy('chat_user_list.seeker_id')
            ->select('recruiter_profiles.office_name as name','chat_user_list.recruiter_id as recruiterId',
                    DB::raw("max(user_chat.message) AS message"),
                    DB::raw("max(user_chat.updated_at) AS timestamp"),
                    DB::raw("max(user_chat.id) AS messageId"),
                    'chat_user_list.id as messageListId','chat_user_list.seeker_id as seekerId',
                    'chat_user_list.recruiter_block as recruiterBlock','chat_user_list.seeker_block as seekerBlock')->get()->toArray();
    }
    
    public static function blockUnblockSeekerOrRecruiter($seekerId, $recruiterId,$type=1){
        $chatResult = static::where('seeker_id',$seekerId)->where('recruiter_id',$recruiterId)->first();
        $blockStatus = 0;
        if($chatResult && $type==1){
            $chatResult->seeker_block = ($chatResult->seeker_block==0)?1:0;
            $blockStatus = $chatResult->seeker_block;
        }elseif($chatResult && $type==2){
            $chatResult->recruiter_block = ($chatResult->recruiter_block==0)?1:0;
            $blockStatus = $chatResult->recruiter_block;
        }
        $chatResult->save();
        return $blockStatus;
    }
}