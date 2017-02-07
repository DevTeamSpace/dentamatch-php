<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
                    ->orwhere('user_chat.from_id','=','chat_user_list.recruiter_id');
            })
            ->where('chat_user_list.seeker_id',$userId)
            ->groupBy('chat_user_list.seeker_id')
            ->select('recruiter_profiles.office_name as name','chat_user_list.recruiter_id as recruiterId',
                    'chat_user_list.seeker_id as seekerId',
                    'chat_user_list.recruiter_block as recruiterBlock','chat_user_list.seeker_block as seekerBlock',
                    'user_chat.message','user_chat.updated_at as timestamp')->get()->toArray();
    }
}