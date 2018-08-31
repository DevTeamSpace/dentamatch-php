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
    
    public static function getSeekerListForChatDashboard($recruiterId){
        $chatUserList = static::join('jobseeker_profiles','jobseeker_profiles.user_id','=','chat_user_list.seeker_id')
            ->join('user_chat',function($query){
                $query->on('user_chat.to_id','=','chat_user_list.seeker_id')
                    ->orOn('user_chat.from_id','=','chat_user_list.seeker_id');
            })
            ->where(function($query) use ($recruiterId){
                return $query->where('user_chat.from_id',$recruiterId)
                    ->orwhere('user_chat.to_id',$recruiterId);
            })
            ->where('chat_user_list.recruiter_id',$recruiterId)
            ->groupBy('chat_user_list.seeker_id')
            ->select('chat_user_list.recruiter_id as recruiterId','jobseeker_profiles.profile_pic',
                    DB::raw("concat(jobseeker_profiles.first_name,' ',jobseeker_profiles.last_name) AS name"),
                    DB::raw("max(user_chat.message) AS message"),
                    DB::raw("max(user_chat.created_at) AS timestamp"),
                    DB::raw("max(user_chat.id) AS messageId"),
                    'chat_user_list.id as messageListId','chat_user_list.seeker_id as seekerId',
                    'chat_user_list.recruiter_block as recruiterBlock','chat_user_list.seeker_block as seekerBlock')
                    ->get();
            
        $messageIds = $chatUserList->pluck('messageId'); 
        $responseData = $chatUserList->toArray();
        
        $chatCountData = UserChat::where('to_id',$recruiterId)->where('read_status',0)
                ->select('from_id as fromId',DB::raw("count(id) AS unreadCount"))
                ->groupBy('from_id')->pluck('unreadCount','fromId');

        $totalCount = $chatCountData->sum();

        $chatData = UserChat::whereIn('id',$messageIds)->pluck('message','id');
        foreach($responseData as $key=>$row){
            if(isset($chatCountData[$row['seekerId']])){
            $responseData[$key]['message'] = $chatData[$row['messageId']];
            $responseData[$key]['timestamp'] = date('M d', strtotime($row['timestamp']));
            $responseData[$key]['unreadCount'] = $chatCountData[$row['seekerId']];
            }else{
                unset($responseData[$key]);
            }
        }
        $chatDatas = array('totalCount' => $totalCount, 'chatData' => $responseData);
        
        return $chatDatas; 
    }
    
    public static function getSeekerListForChat($recruiterId){
        $chatUserList = static::join('jobseeker_profiles','jobseeker_profiles.user_id','=','chat_user_list.seeker_id')
            ->join('job_titles','jobseeker_profiles.job_titile_id','=','job_titles.id')
            ->join('user_chat',function($query){
                $query->on('user_chat.to_id','=','chat_user_list.seeker_id')
                    ->orOn('user_chat.from_id','=','chat_user_list.seeker_id');
            })
            ->where(function($query) use ($recruiterId){
                return $query->where('user_chat.from_id',$recruiterId)
                    ->orwhere('user_chat.to_id',$recruiterId);
            })
            ->where('chat_user_list.recruiter_id',$recruiterId)
            ->groupBy('chat_user_list.seeker_id')
            ->select('chat_user_list.recruiter_id as recruiterId','jobseeker_profiles.profile_pic',
                    DB::raw("concat(jobseeker_profiles.first_name,' ',jobseeker_profiles.last_name) AS name"),
                    DB::raw("max(user_chat.message) AS message"),
                    DB::raw("max(user_chat.created_at) AS timestamp"),
                    DB::raw("max(user_chat.id) AS messageId"),'job_titles.jobtitle_name as jobTitle',
                    'chat_user_list.id as messageListId','chat_user_list.seeker_id as seekerId',
                    'chat_user_list.recruiter_block as recruiterBlock','chat_user_list.seeker_block as seekerBlock')
                    ->orderBy('timestamp','desc')
                    ->get();
            
        $messageIds = $chatUserList->pluck('messageId'); 
        $responseData = $chatUserList->toArray();
        $chatData = UserChat::whereIn('id',$messageIds)->pluck('message','id');
        foreach($responseData as $key=>$row){
            $responseData[$key]['message'] = $chatData[$row['messageId']];
            $responseData[$key]['timestamp'] = date('M d', strtotime($row['timestamp']));
            }
        return $responseData;
        
    }
    
    public static function getRecruiterListForChat($userId){
        $chatUserList = static::join('recruiter_profiles','recruiter_profiles.user_id','=','chat_user_list.recruiter_id')
            ->leftjoin('user_chat',function($query) use($userId){
                $query->on(DB::raw('(user_chat.to_id=chat_user_list.recruiter_id and user_chat.from_id='.$userId.')'))
                    ->orOn(DB::raw('(user_chat.from_id=chat_user_list.recruiter_id and user_chat.to_id='.$userId.')'));
            })
//            ->where(function($query) use ($userId){
//                return $query->where('user_chat.from_id',$userId)
//                    ->orwhere('user_chat.to_id',$userId);
//            })
            ->where('chat_user_list.seeker_id',$userId)
            ->groupBy('chat_user_list.recruiter_id')
            ->select('recruiter_profiles.office_name as name','chat_user_list.recruiter_id as recruiterId',
                    DB::raw("max(user_chat.message) AS message"),
                    DB::raw("max(user_chat.created_at) AS timestamp"),
                    DB::raw("max(user_chat.id) AS messageId"),
                    'chat_user_list.id as messageListId','chat_user_list.seeker_id as seekerId',
                    'chat_user_list.recruiter_block as recruiterBlock','chat_user_list.seeker_block as seekerBlock')->get();
    
        $messageIds = $chatUserList->pluck('messageId'); 
        $responseData = $chatUserList->toArray();
        
        $chatCountData = UserChat::where('to_id',$userId)->where('read_status',0)
                ->select('from_id as fromId',DB::raw("count(id) AS unreadCount"))
                ->groupBy('from_id')->pluck('unreadCount','fromId');
        
        $chatData = UserChat::whereIn('id',$messageIds)->pluck('message','id');
        foreach($responseData as $key=>$row){
            $responseData[$key]['message'] = ($row['messageId']!=null)?$chatData[$row['messageId']]:'';
            $responseData[$key]['timestamp'] = strtotime($row['timestamp'])*1000;
            $responseData[$key]['unreadCount'] = isset($chatCountData[$row['recruiterId']])?$chatCountData[$row['recruiterId']]:0;
        }
        return $responseData;
    }
    
    public static function blockUnblockSeekerOrRecruiter($seekerId, $recruiterId, $blockStatus,$type=1){
        $chatResult = static::where('seeker_id',$seekerId)->where('recruiter_id',$recruiterId)->first();
        if($chatResult && $type==1){
            $chatResult->seeker_block = $blockStatus;
        }elseif($chatResult && $type==2){
            $chatResult->recruiter_block = $blockStatus;
        }
        $chatResult->save();
        return $blockStatus;
    }
    
    public function checkAndSaveUserToChatList(){
        $userChatList = static::where('recruiter_id',$this->recruiter_id)->where('seeker_id',$this->seeker_id)->first();
        if(empty($userChatList)){
            $this->save();
        }

    }
    
    public static function getBlockedRecruiters($seekerId)
    {
        $return = [];
        $blockedUserModel = static::select('recruiter_id')->where('seeker_block', 1)->where('seeker_id', $seekerId)->get();
        
        if($blockedUserModel)
        {
            $return = $blockedUserModel->toArray();
        }
        return $return;
    }
}