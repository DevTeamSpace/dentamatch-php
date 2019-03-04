<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\JobAppliedStatus;
use App\Enums\JobType;
use App\Http\Controllers\Controller;
use App\Models\JobLists;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Models\ChatUserLists;
use App\Helpers\ApiResponse;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('ApiAuth');
    }

    /**
     * Method to get chat Recruiter List
     * @param Request $request
     * @return Response
     */
    public function getChatsWithRecruiters(Request $request)
    {
        $recruiterList = ChatUserLists::getRecruiterListForChat($request->apiUserId);
        return ApiResponse::successResponse('', ['list' => $recruiterList]);
    }

    /**
     * Start a chat with recruiter
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postInitChatWithRecruiter(Request $request)
    {
        $this->validate($request, [
            'recruiterId' => 'required|integer'
        ]);

        $seekerId = $request->apiUserId;
        $recruiterId = $request->recruiterId;

        $chatExists = ChatUserLists::query()->where('seeker_id', $seekerId)->where('recruiter_id', $recruiterId)->count();
        if (!$chatExists) {
            $jl = JobLists::whereSeekerId($seekerId)
                ->whereAppliedStatus(JobAppliedStatus::INVITED)
                ->whereHas('job', function (Builder $subquery) use ($recruiterId) {
                    $subquery->where('job_type', JobType::TEMPORARY)
                        ->whereHas('recruiterOffice.recruiter', function (Builder $subsubquery) use ($recruiterId) {
                            $subsubquery->whereId($recruiterId);
                        });
                })
                ->first();
            if (!$jl) {
                return ApiResponse::errorResponse("Not available");
            }
            ChatUserLists::create(['recruiter_id' => $recruiterId, 'seeker_id' => $seekerId]);
        }

        $allChats = ChatUserLists::getRecruiterListForChat($seekerId);
        $chatWithRecruiter = array_first($allChats, function ($chat) use ($recruiterId) {
            return $chat['recruiterId'] == $recruiterId;
        });

        return ApiResponse::successResponse('', $chatWithRecruiter);
    }

    /**
     * Method to Block Unblock Recruiter chat
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function chatBlockUnblockRecruiter(Request $request)
    {
        $this->validate($request, [
            'recruiterId' => 'required',
            'blockStatus' => 'required|in:0,1,',
        ]);
        $blockStatus = ChatUserLists::blockUnblockSeekerOrRecruiter($request->apiUserId, $request->recruiterId, $request->blockStatus);
        return ApiResponse::successResponse(trans("messages.recruiter_blocked"), ['recruiterId' => $request->recruiterId, 'blockStatus' => $blockStatus]);
    }

}