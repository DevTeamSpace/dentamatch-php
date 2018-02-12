@extends('web.layouts.dashboard')
@section('content')
<div class="container">
    <div class="dashboarFinalBox mr-t-25">
        @if(!empty($notificationAdminModel))
            <div class="alert alert-info customInfo">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <input type="hidden" class="recId" name="id" value="{{ $notificationAdminModel->id }}">
                <div class="infoContent">
                    <h3>Announcements</h3>
                    <?php echo $notificationAdmin->message ?>
                </div>
            </div>
        @endif

        <div class="dashboarFinalInnerBox">
            <div class="row">
                <div class="col-sm-6">
                    <div class="commonBox welcomeLeft">
                        <div class="welcomeLeftheader">
                            <h6>WELCOME</h6>
                            <h4>{{ Session::get('userData.profile.office_name') }}</h4>
                            <a href="/edit-profile" id="dashEdit">Edit profile</a>
                        </div>
                        <div class="welcomeContent">
                            
                            <h4>{{$currentDay}}<br>{{ $currentDate }}</h4>
                            <div class="tHire">
                                <span>{{ count($hiredListByCurrentDate) }}</span>   
                                <p>Today’s Hire</p>
                            </div>

                            <ul class="dashboarFinalList">
                                @if(!empty($hiredListByCurrentDate))
                                    @foreach($hiredListByCurrentDate as $hiredJobseeker)
                                        <li>
                                            <div class="dashListImgBlock">
                                                <div class="dashListImg">
                                                    <img class="dashListImg" src="{{ url("image/66/66/?src=" .$hiredJobseeker['profile_pic']) }}" alt="...">
                                                </div>
                                                <div class="dashListImgContent">
                                                    <h6>{{ $hiredJobseeker['first_name'] }} {{ $hiredJobseeker['last_name'] }}</h6>
                                                    <p>{{ $hiredJobseeker['jobtitle_name'] }}</p>  
                                                </div>
                                                <a href="/job/details/{{ $hiredJobseeker['id'] }}" class="dashListRightPos">View Job details</a>
                                            </div>
                                            <div class="line"></div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="commonBox ">
                        <div class="welcomeContent lastMsg">
                            <h4>Latest Messages</h4>
                            <div class="tHire unread">
                                    {{ count($latestMessage) }} Unread
                            </div>
                            <ul class="dashboarFinalList"> 
                                @if(!empty($latestMessage))
                                @foreach ($latestMessage as $seeker)
                                    <li>
                                        <div class="dashListImgBlock">
                                            <div class="dashListImg">
                                                <img class="dashListImg" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}" alt="...">
                                            </div>
                                            <div class="dashListImgContent msgImg">
                                                <h6>{{ $seeker['name'] }}</h6>
                                                <p>{{ $seeker['message'] }}</p>  
                                            </div>
                                            <div class="msgNotficaitonBlock">
                                                <span>{{ ($seeker['timestamp']) }}</span>
                                                <div class="msgNotification ">{{ ($seeker['unreadCount']) }}</div></div>
                                        </div>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                            <div class="viewAll"><a href="{{url('/chat')}}">View all</a></div>

                        </div>
                    </div>
                    <div class="commonBox">
                        <div class="welcomeContent lastMsg">
                            <h4>Latest Notifications</h4>
                            <div class="tHire unread">
                                {{ $latestNotifications['total'] }} Unread
                            </div>
                            <ul class="dashboarFinalList  latestNotification">
                                @if(!empty($latestNotifications['data']))
                                @foreach($latestNotifications['data'] as $notification)
                                <?php $data = json_decode($notification->notification_data); ?>
                                    <li>
                                        <div class="dashListImgBlock">
                                            <div class="dashListImg"></div>
                                            <div class="dashListImgContent msgImg">
                                                <h6 class="media-heading"><?php echo strip_tags($data->message); ?></p></h6>
                                                <p class="justNow"><span class="icon-clock"></span>{{ $notification->created_at->diffForHumans() }}</p> 
                                            </div>
                                            <div class="onlineDot border-radius">
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="viewAll"><a href="/notification-lists">View all</a></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="commonBox">
                        <div class="welcomeContent lastMsg">
                            <h4>What to do next?</h4>
                            <ul class="dashboadPostBlock">
                                <li>
                                    <a class="modalClick btn btn-link mr-r-20" data-toggle="modal" data-target="#jobTemplate"><img src="{{asset('web/images/dentamatch-folder.png')}}" width="45"></a>
                                    Post New Job
                                </li>
                                <li>
                                    <a href="/jobtemplates/create"><img src="{{asset('web/images/dentamatch-plussign.png')}}" width="45"></a>
                                    Create Template
                                </li>
                                <li>
                                    <a href="/job/lists"><img src="{{asset('web/images/dentamatch-foldercurrentJob.png')}}" width="45"></a>
                                    View Current Jobs
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="commonBox">
                        <div class="welcomeContent lastMsg">
                            <h4>This week’s view</h4>
                            @if(count($currentWeekCalendar)>0)
                                @include('web.recuriterJob.dashboard-calendar-data')
                            @endif
                            <div class="viewAll"><a href="{{url('/calender')}}">View calendar</a></div>
                        </div>
                    </div>
                    <div class="commonBox">
                        <div class="welcomeContent lastMsg">
                            <h4>Recently Posted Jobs</h4>
                            @if(count($jobList)>0)
                                @include('web.recuriterJob.dashboard-job-data')
                            @endif
                        </div>
                        <div class="viewAll"><a href="/job/lists">View all</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection
    @section('js')
<script>
$('.close').on('click',function (){
    var Id = $('.recId').val();
   $.ajax({
   url :'notification/seen/' + Id,
   type:'GET',
   dataType: 'json',
   success: function() {
       location.reload();
   }
  }); 
});

</script>    
    @endsection